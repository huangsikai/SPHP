<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/15
 */

namespace SPHPCore\Lib\Request;


class Parameter
{

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';

    /**
     * @param string $index
     * @param string $default
     * @param string $filter
     * @return array|string
     */
    public function get($index, $default, $filter)
    {
        $input = $this->getInput(self::METHOD_GET);
        if(!empty($index)){
            if(isset($input[$index])){
                return $this->filter($input[$index],$filter);
            }else{
                return $default;
            }
        }else{
            return $this->filter($input,$filter);
        }
    }

    /**
     * @param $index
     * @param $default
     * @param $filter
     * @return array|string
     */
    public function post($index, $default, $filter)
    {
        $input = $this->getInput(self::METHOD_POST);
        if(!empty($index)){
            if(isset($input[$index])){
                return $this->filter($input[$index],$filter);
            }else{
                return $default;
            }
        }else{
            return $this->filter($input,$filter);
        }
    }

    /**
     * @param $index
     * @return bool
     */
    public function isPost($index)
    {
        return empty($index) ? $_SERVER['REQUEST_METHOD'] === 'POST' : isset($_POST[$index]);
    }

    /**
     * @return bool
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * @return bool
     */
    function isSsl() {
        if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
            return true;
        }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
            return true;
        }
        return false;
    }

    /**
     * @param $method
     * @return array
     */
    private function getInput($method)
    {
        switch($method){
            case self::METHOD_GET : $input = $_GET;
                break;
            case self::METHOD_POST : $input = $_POST;
                break;
            case self::METHOD_PUT : $input = file_get_contents('php://input');
                break;
            default : $input = $_GET;
                break;
        }
        return $input;
    }

    /**
     * @param $data
     * @param $filter
     * @return array|string
     */
    private function filter($data, $filter)
    {
        if(!isset($filter)){
            $filters = array(array($this,'removeSql'),'htmlspecialchars');
            foreach($filters as $filter){
                $data = $this->callFilter($data,$filter);
            }
        }else{
            $isCall = false;
            if(is_array($filter) && isset($filter[0]) && isset($filter[1])){
                $isCall = method_exists($filter[0],$filter[1]);
            }else if(is_string($filter)){
                $isCall = function_exists($filter);
            }
            $isCall ? $data = $this->callFilter($data,$filter) : true;
        }
        return $data;
    }

    /**
     * @param $data
     * @param $filter
     * @return array|string
     */
    private function callFilter($data,$filter)
    {
        $result = '';
        if(is_array($data)){
            $result = array();
            foreach($data as $key => $val) {
                $result[$key] = is_array($val) ? $this->callFilter($val,$filter) : call_user_func($filter, $val);
            }
        }else if(is_string($data)){
            $result = $filter($data);
        }
        return $result;
    }

    /**
     * @param $data
     * @return array|mixed
     */
    private function removeSql($data)
    {
        $patterns = array(
            "/\bunion\b/i",
            "/\bselect\b/i",
            "/\bupdate\b/i",
            "/\bdelete\b/i",
            "/\boutfile\b/i",
            "/\bor\b/i",
            "/\bchar\b/i",
            "/\bconcat\b/i",
            "/\btruncate\b/i",
            "/\bdrop\b/i",
        );
        $replaces = array(
            'ｕｎｉｏｎ',
            'ｓｅｌｅｃｔ',
            'ｕｐｄａｔｅ',
            'ｄｅｌｅｔｅ',
            'ｏｕｔｆｉｌｅ',
            'ｏｒ',
            'ｃｈａｒ',
            'ｃｏｎｃａｔ',
            'ｔｒｕｎｃａｔｅ',
            'ｄｒｏｐ',
        );
        return is_array($data) ? array_map(array($this,'removeSql'),$data) : preg_replace($patterns, $replaces, $data);
    }
}