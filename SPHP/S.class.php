<?php

/**
 * Describe: SPHP 框架入口类
 * Author: Sky
 * Date: 2017/2/5
 */
if(version_compare(PHP_VERSION,'5.3.0','<')){
    throw new Exception('PHP版本低于5.3');
}
defined('DEBUG') OR define('DEBUG',false);
defined('SUB_DIR') OR define('SUB_DIR','');
defined('BASE_PATH') OR define('BASE_PATH',rtrim($_SERVER['DOCUMENT_ROOT'],'/').(SUB_DIR ? '/'.trim(SUB_DIR,'/') : ''));
defined('RUNTIME_DIR') OR define('RUNTIME_DIR','Runtime');
defined('APP_NAME') OR define('APP_NAME','App');
defined('MODULE') OR define('MODULE','Home');
defined('MODULE_DIR') OR define('MODULE_DIR','Module');
defined('CONTROLLER_DIR') OR define('CONTROLLER_DIR','Controller');
defined('MODEL_DIR') OR define('MODEL_DIR','Model');
defined('VIEW_DIR') OR define('VIEW_DIR','View');
defined('CONFIG_DIR') OR define('CONFIG_DIR','Config');
defined('FUN_DIR') OR define('FUN_DIR','Function');
defined('HOOK_DIR') OR define('HOOK_DIR','Hook');
define('CORE_PATH',realpath(__DIR__.'/'));
defined('PATH_NOISE') OR define('PATH_NOISE','/\/');
defined('PATH_OS_SLASH') OR define('PATH_OS_SLASH',DIRECTORY_SEPARATOR);
defined('ENV') OR define('ENV','develop');
class S
{
    public static function Main(){
        try{
            require_once 'Macro.php';
            require_once 'Init.class.php';
            $init = new \SPHPCore\Init();
            $init->start();
        }catch (SPHPException $e){}
    }

}