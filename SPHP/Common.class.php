<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore;


class Common {

    private static $_loadFiles = null;

    private static $_constant_params = null;

    /**
     * 生成连接
     * @param string $link
     * @param null $query_string
     * @return string
     */
    public static function link($link='',$query_string = null){
        $dir= SUB_DIR;
        if(is_string($link)){
            $link = trim($link,PATH_NOISE);
            if (!empty($dir) && $dir !== '/') {
                if (strpos($link, $dir) !== 0)
                    $link = '/'.trim($dir,PATH_NOISE) . '/' . $link;
            }else{
                if (empty($dir)) $dir = '/';
                $link = $dir . $link;
            }
        }

        if(!empty(self::$_constant_params) && is_array(self::$_constant_params)){
            foreach(self::$_constant_params as $k){
                if((!isset($query_string[$k]) && isset($_GET[$k]))) $query_string[$k] = $_GET[$k];
            }
        }

        if (!empty($query_string)) {
            $parse = parse_url($link);
            if (!empty($parse['query']))
                parse_str($parse['query'], $parse['query']);
            if (is_string($query_string))
                parse_str($query_string, $query_string);
            if (!empty($parse['query']))
                $query_string = array_merge($parse['query'], $query_string);
            if (!empty($query_string)) {
                foreach ($query_string as $key => $value) {
                    if ($value === null || $value === false)
                        unset($query_string[$key]);
                }
            }
            $query_string = http_build_query($query_string);
            $link = $parse['path'];

            if (!empty($query_string))
                $link .= '?' . $query_string;
        }
        return $link;
    }

    /**
     * 设置持久参数
     * @param null $params
     * @return null
     */
    public static function setConstantParams($params=null){
        if(!empty($params)){
            self::$_constant_params = $params;
        }
        return self::$_constant_params;
    }


    /**
     *获取应用的文件路径
     * @param $filePath
     * @return mixed
     */
    public static function getFilePath($filePath){
        $fullPath = realpath(BASE_PATH.PATH_OS_SLASH.APP_NAME.PATH_OS_SLASH.$filePath);
        return $fullPath;
    }


    /**
     * @param $file
     * @return bool
     */
    public static function loadFile($file){
        $fileHash = md5(serialize($file));
        if(!isset(self::$_loadFiles[$fileHash])){
            if(is_file($file)){
                self::$_loadFiles[$fileHash] = include_once $file;
            }else{
                return false;
            }
        }
        return self::$_loadFiles[$fileHash];
    }

    public static function getClientIp4(){
        $ip = null;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        return sprintf("%u",ip2long($ip)) ? $ip : '0.0.0.0';
    }

    public static function getClientIp6(){return '::1';}



    /**
     * 分页数组
     * @param int $current_page
     * @param int $total_page
     * @param int $dis_page
     * @return array
     */
    public static function pages($current_page = 1,$total_page = 1,$dis_page = 5){
        $half_page = floor($dis_page / 2);  //偏移数
        $current_page < 1 && $current_page = 1;
        $current_page > $total_page && $current_page = $total_page;
        $min_page = $current_page - $half_page < 1 ? 1 : $current_page - $half_page ;
        $max_page = $current_page + $half_page;
        $max_page < $dis_page &&  $max_page += $dis_page - $max_page;
        $max_page > $total_page && $max_page = $total_page;
        $pages = array(
            'min_page'=>$min_page,
            'current_page'=>$current_page,
            'max_page'=>$max_page,
            'front_page' => $current_page-1 < 1 ? 1 :$current_page-1,
            'next_page' => $current_page+1 > $total_page ? $total_page : $current_page+1,
            'last_page'=>$total_page,
        );
        return $pages;
    }


    /**
     * 生成xml编码
     * @param $data
     * @param string $root
     * @param string $item
     * @param string $attr
     * @param string $id
     * @param string $encoding
     * @return string
     */
    public static function xml_encode($data, $root='root', $item='item', $attr='', $id='id', $encoding='utf-8') {
        if(is_array($attr)){
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr   = trim($attr);
        $attr   = empty($attr) ? '' : " {$attr}";
        $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml   .= "<{$root}{$attr}>";
        $xml   .= self::data_to_xml($data, $item, $id);
        $xml   .= "</{$root}>";
        return $xml;
    }

    /**
     * 数组转xml格式
     * @param $data
     * @param string $item
     * @param string $id
     * @return string
     */
    public static function data_to_xml($data, $item='item', $id='id') {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if(is_numeric($key)){
                $id && $attr = " {$id}=\"{$key}\"";
                $key  = $item;
            }
            $xml    .=  "<{$key}{$attr}>";
            $xml    .=  (is_array($val) || is_object($val)) ? self::data_to_xml($val, $item, $id) : $val;
            $xml    .=  "</{$key}>";
        }
        return $xml;
    }


    /**
     * 经典概率算法
     * @param array $proArr   [k]=>proSum
     * @return int|string
     */
    public static function probability($proArr=[]){
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);             //抽取随机数
            if ($randNum <= $proCur) {
                $result = $key;                         //得出结果
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

}