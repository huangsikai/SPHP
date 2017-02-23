<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/16
 */

namespace App\Hook;



use SPHPCore\Hook;


class Base extends Hook
{
    /**
     * 内置钩子开关
     * @var bool true 打开 | false 关闭
     */
    public $on = false;

    /**
     * 执行控制器前钩子
     * @param string $controllerName   控制器名
     * @param string $actionName       方法名
     */
    public function controller($controllerName, $actionName)
    {
        // TODO: Implement controller() method.
    }

    /**
     * 获取model数据后
     * @param string $modelName 模型名
     * @param array $data   数据
     */
    public function model($modelName, $data)
    {
        // TODO: Implement model() method.
    }

    /**
     * 加载视图布局前
     * @param string $layoutName 布局名称
     */
    public function layout($layoutName)
    {
        // TODO: Implement layout() method.
    }

    /**
     * 加载模板视图模板前
     * @param string $viewName 视图名称
     */
    public function template($viewName)
    {
        // TODO: Implement template() method.
    }

    /**
     * 默认系统异常
     * @param string $message  异常消息
     * @param array $trace     跟踪文件信息
     * @param string $traceStr 跟踪文件信息字符串
     */
    public function exception($message, $trace, $traceStr)
    {
        // TODO: Implement exception() method.
    }

    /**
     * 默认系统错误
     * @param int $type 错误类型
     * @param string $message 错误消息
     * @param string $file 错误文件
     * @param string $line 错误行数
     */
    public function error($type, $message, $file, $line)
    {
        $appError = new AppError();
        if($type == E_NOTICE){
            $appError->notice();
        }
    }

    /**
     * php脚本执行完成
     * @param int $time 时间戳
     */
    public function shutdown($time)
    {
        // TODO: Implement shutdown() method.
    }

}