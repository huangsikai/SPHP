<?php
/**
 * Describe: 初始化类
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore;


use SPHPCore\Lib\Action;
use SPHPCore\Lib\Config;
use SPHPCore\Lib\HookAction;

class Init
{
    public function __construct()
    {
        if(isset($GLOBALS['SPHPInit'])) return;
        $GLOBALS['SPHPInit'] = true;
        spl_autoload_register(__NAMESPACE__.'\Init::SPHPAutoload');
        register_shutdown_function(__NAMESPACE__.'\Init::SPHPShutdown');
        set_error_handler(__NAMESPACE__.'\Init::SPHPError');
        set_exception_handler(__NAMESPACE__.'\Init::SPHPException');
        date_default_timezone_set('PRC');
        error_reporting(0);
        Config::load();
        HookAction::load();
    }


    /**
     * 类库加载
     * @param $class
     */
    public static function SPHPAutoload($class)
    {
        $classHash = md5($class);
        if(isset($GLOBALS['SPHPLoadClass'][$classHash])){
            return;
        }
        if(in_array(strstr($class, '\\', true),array('SPHPCore'))){
            $name           =   strstr($class, '\\');
            $path       =   CORE_PATH;
        }else{
            $name           =   '\\'.$class;
            $path       =   BASE_PATH;
        }
        $file =  $path.$name;
        $file = str_replace('\\',PATH_OS_SLASH, $file).'.class.php';
        if(file_exists($file)) {
            $GLOBALS['SPHPLoadClass'][$classHash] = require_once $file;
        }
        unset($class,$classHash,$name,$path,$file);
    }


    /**
     * 脚本终止处理
     */
    public static function SPHPShutdown()
    {
        if($error = error_get_last()){
            self::SPHPError($error['type'],$error['message'],$error['file'],$error['line']);
        }
        HookAction::doHook('shutdown',array(time()));
    }


    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */
    public static function SPHPException($e)
    {
        $exception = array(
            'message'=>$e->getMessage(),
            'trace'=>$e->getTrace(),
            'traceStr'=>$e->getTraceAsString(),
        );
        if(DEBUG){
            header('Content-Type:text/html; charset='.Config::getValue(SPHP_CHARSET));
            print_r($exception);
        }
        HookAction::doHook('exception',$exception);
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $type 错误类型
     * @param string $message 错误信息
     * @param string $file 错误文件
     * @param int $line 错误行数
     * @return void
     */
    public static function SPHPError($type, $message, $file, $line)
    {
        if(DEBUG){
            header('Content-Type:text/html; charset='.Config::getValue(SPHP_CHARSET));
            print_r(array(
                'type'=>$type,
                'message'=>$message,
                'file'=>$file,
                'line'=>$line,
            ));
        }
        HookAction::doHook('error',array($type,$message,$file,$line));
    }


    /**
     * @throws \Exception
     */
    public function start(){
        $GLOBALS[SPHP_ACTION] = new Action();
        $GLOBALS[SPHP_ACTION]->dispatch();
    }
}