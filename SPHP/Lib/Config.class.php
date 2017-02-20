<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore\Lib;


use SPHPCore\Common;

class Config {
    private static $_envs = array('develop','test','product');
    private static $_configs = array();
    private static $_routes = array();

    public static function load(){
        if(empty(self::$_configs)){
            $env = defined('ENV') && in_array(ENV,self::$_envs) ? ENV : self::$_envs[0];
            $configFilecs[] = Common::getFilePath(CONFIG_DIR.PATH_OS_SLASH.$env.'.php');
            $configFilecs[] = Common::getFilePath(MODULE_DIR.PATH_OS_SLASH.MODULE.PATH_OS_SLASH.CONFIG_DIR.PATH_OS_SLASH.$env.'.php');
            foreach($configFilecs as $configFilec){
                $config = Common::loadFile($configFilec);
                if(!empty($config) && is_array($config)){
                    self::$_configs = array_merge(self::$_configs,$config);
                }
            }
            unset($configFilecs,$config,$env);
        }

    }

    /**
     * 获取配置文件值
     * @param $index
     * @return string
     */
    public static function getValue($index){
        return isset(self::$_configs[$index]) ? self::$_configs[$index] : '';
    }

    /**
     * 设置配置文件值
     * @param $index
     * @param $value
     */
    public static function setValue($index,$value){
        if(!empty($index) && isset($value)){
            self::$_configs[$index] = $value;
        }
    }

    /**
     * 获取路由配置
     * @return array|bool
     */
    public static function getRoute(){
        if(empty(self::$_routes)){
            $routeFile = Common::getFilePath(MODULE_DIR.PATH_OS_SLASH.MODULE.PATH_OS_SLASH.CONFIG_DIR.PATH_OS_SLASH.'Route.php');
            self::$_routes = Common::loadFile($routeFile);
        }
        return self::$_routes;
    }
}