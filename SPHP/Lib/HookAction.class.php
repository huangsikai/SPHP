<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/16
 */

namespace SPHPCore\Lib;



use Cgg\Models\Variable;

class HookAction
{
    const FUN_SUFFIX = '.php';
    private static $_hooks = array();

    public static function load(){
        self::fun();
        self::base();
    }

    private static function fun()
    {
        $functions = Config::getValue(SPHP_FUNSTIONS);
        if(!empty($functions) && is_array($functions)){
            $funDir = BASE_PATH.PATH_OS_SLASH.APP_NAME.PATH_OS_SLASH.FUN_DIR.PATH_OS_SLASH;
            foreach($functions as $function){
                $filename = $funDir.$function.self::FUN_SUFFIX;
                if(is_file($filename)){
                    include_once $filename;
                }
            }
        }
    }

    private static function base(){
        $base = '\\'.APP_NAME.'\\Hook\Base';
        if(class_exists($base) && is_subclass_of($base,'\SPHPCore\Hook'))
        {
            $baseHook = new $base();
            if(!$baseHook->on) return;
            $ref = new \ReflectionClass('\SPHPCore\Hook');
            $methods = $ref->getMethods(\ReflectionMethod::IS_ABSTRACT);
            foreach($methods as $method){
                self::addHook($method->getName(),array($baseHook,$method->getName()),0);
            }
        }
    }


    /**
     * @param $name
     * @param $hook
     * @param int $priority
     */
    public static function addHook($name,$hook,$priority = 10){
        self::$_hooks[$name][$priority] = function($parameter) use($hook){
            return call_user_func_array($hook,$parameter);
        };
    }

    /**
     * @param $name
     * @param array $parameter
     */
    public static function doHook($name,$parameter = array()){
        if(isset(self::$_hooks[$name])){
            arsort(self::$_hooks[$name]);
            foreach(self::$_hooks[$name] as $hook){
                try{
                    $hook($parameter);
                }catch (SPHPException $e){

                }
            }
        }
    }

}