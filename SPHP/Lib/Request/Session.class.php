<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/15
 */

namespace SPHPCore\Lib\Request;


use SPHPCore\Lib\Config;

class Session
{

    private function init(){
        $ini = array(
            'save_path' => 1, 'name' => 1, 'save_handler' => 1,
            'gc_probability' => 1, 'gc_divisor' => 1, 'gc_maxlifetime' => 1,
            'serialize_handler' => 1, 'cookie_lifetime' => 1, 'cookie_path' => 1,
            'cookie_domain' => 1, 'cookie_secure' => 1, 'cookie_httponly' => 1,
            'use_strict_mode' => 1, 'use_cookies' => 1, 'use_only_cookies' => 1,
            'referer_check' => 1, 'entropy_file' => 1, 'entropy_length' => 1,
            'cache_limiter' => 1, 'cache_expire' => 1, 'use_trans_sid' => 1,
            'bug_compat_42' => 1, 'bug_compat_warn' => 1, 'hash_function' => 1,
            'hash_bits_per_character' => 1, 'tags' => 1
        );
        $config = Config::getValue(SPHP_SESSION);
        if($config && is_array($config)){
            foreach($config as $key => $value){
                if(isset($ini[$key])){
                    $setStr = ($key == 'tags' ? 'url_rewriter' : 'session') . '.' . $key;
                    ini_set($setStr,$value);
                }
            }
        }
    }

    public function __construct()
    {
        if(!session_id()){
            $this->init();
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