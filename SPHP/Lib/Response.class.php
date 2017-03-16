<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore\Lib;


use SPHPCore\Common;

class Response
{
    const
        JSON = 'json',
        TEXT = 'text',
        VIEW = 'view';
    private
        $_dispatch,
        $_expire;

    private $_types = array(
        'view'      => 'text/html',
        'text'      => 'text/html',
        'txt'       => 'text/plain',
        'css'       => 'text/css',
        'xml'       => 'application/xml',
        'json'      => 'application/json',
        'js'        => 'application/javascript',
        'gif'       => 'image/gif',
        'png'       => 'image/png',
        'jpg'       => 'image/jpg',
        'stream'    => 'application/octet-stream',
    );

    private $_type;


    public function __construct(){
        $this->_dispatch = $GLOBALS[SPHP_DISPATCH];
    }


    /**
     * @return mixed
     */
    public function getExpire(){
        return $this->_expire ? $this->_expire : 0;
    }

    /**
     * @param mixed $expire
     */
    public function setExpire($expire){
        $this->_expire = $expire;
    }


    /**
     * 输出文字
     * @param $data
     */
    public function text($data){
        $this->_type = self::TEXT;
        $this->sendHeader();
        exit($data);
    }

    /**
     * 输出json
     * @param $data
     */
    public function json($data){
        $this->_type = self::JSON;
        $this->sendHeader();
        if(isset($_GET['callback'])) exit($_GET['callback'].'('.json_encode($data).');');
        exit(json_encode($data));
    }

    /**
     * 显示视图
     * @param string $viewName
     */
    public function view($viewName = ""){
        $this->_type = self::VIEW;
        $this->sendHeader();
        $view = $this->_dispatch->getView();
        if(!empty($viewName))
            $view->setViewName($viewName);
        $view->display();
    }

    /**
     * 显示图片
     * @param $path
     * @throws \Exception
     */
    public function img($path){
        if(is_file($path)){
            list($width, $height, $typeCode) = getimagesize($path);
            switch($typeCode){
                case 1: $this->_type = 'gif';break;
                case 2: $this->_type = 'jpg';break;
                case 3: $this->_type = 'png';break;
                default : $this->_type = 'jpg';break;
            }
            $this->sendHeader();
            exit(file_get_contents($path));
        }else{
            throw new \Exception('图片路径不存在');
        }
    }

    /**
     * 跳转
     * @param $link
     * @param null $message
     */
    public function redirect($link,$message = null){
        if(strpos($link,'http') === 0){
            header('Location: '.$link); exit;
        }else{
            $link = Common::link($link);
            $text = $message ? '<script>alert("'.$message.'");window.location.href="'.$link.'"</script>' : '<script>window.location.href="'.$link.'"</script>';
            $this->text($text);
        }
    }



    private function sendHeader(){
        if(headers_sent()) return;
        header('Content-Type: '.$this->getContentType().'; charset='.Config::getValue(SPHP_CHARSET));
        header('Cache-control: max-age='.$this->getExpire());
        header("Pragma:private");
        header("expires: ".gmdate("D, d M Y H:i:s", time()+$this->getExpire())."  GMT");
        header('X-Powered-By:SPHP');
    }

    private function getContentType(){
        return $this->_types[$this->_type];
    }


    public function __destruct() {
        $this->_dispatch = null;
        $this->_expire = null;
    }
}