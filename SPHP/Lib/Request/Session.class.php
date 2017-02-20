<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/15
 */

namespace SPHPCore\Lib\Request;


class Session
{

    private function init(){

    }

    public function __construct()
    {
        $this->init();
        if(!session_id()){
            session_start();
        }
    }


    public function set($index,$value)
    {
        $_SESSION[$index] = $value;
    }

    public function get($index)
    {
        return isset($_SESSION[$index]) ? $_SESSION[$index] : null;
    }

    public function delete($index)
    {
        unset($_SESSION[$index]);
    }

    public function clear(){
        session_unset();
        session_destroy();
    }
}