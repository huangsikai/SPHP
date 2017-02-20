<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore\Lib\Mvc;


use SPHPCore\Lib\HookAction;

abstract class Controller
{
    private $_action;
    protected $request;
    protected $response;

    /**
     * 转发
     * @param $controllerName
     * @param $actionName
     */
    protected function forward($controllerName,$actionName){
        $this->_action->call($controllerName,$actionName);
    }

    /**
     * 传递视图参数
     * @param $index
     * @param $value
     */
    protected function assign($index,$value){
        $this->_action->setAssign($index,$value);
    }

    /**
     * Controller constructor.
     * @param $request
     * @param $response
     */
    final public function __construct($request,$response){
        $this->_action = $GLOBALS[SPHP_ACTION];
        $this->request = $request;
        $this->response = $response;
        unset($request,$response);
    }

    /**
     * 初始化方法 给子控制器重写
     */
    public function __init(){}

    /**
     * 未知方法 给子控制器重写
     */
    public function unknown(){}

    /**
     * 设置布局名称
     * @param $layout
     */
    public function setLayout($layout){
        $this->_action->getView()->setLayout($layout);
    }

    /**
     * 获取数据库
     * @return mixed
     */
    public function db(){
       return $this->_action->getDb();
    }

    /**
     * 获取表对象
     * @param $tableName
     * @param string $primary
     * @return mixed
     */
    public function tb($tableName, $primary = "id"){
        return $this->_action->getTable($tableName, $primary);
    }

    /**
     * @param $index
     * @param $value
     */
    public function __set($index, $value){
        $this->assign($index,$value);
        $this->$index = $value;
    }

    /**
     * @param $index
     * @return string
     */
    public function __get($index){
        return '';
    }

    /**
     * 添加钩子
     * @param $name
     * @param $hook
     * @param int $priority
     */
    public function addHook($name,$hook,$priority = 10){
        HookAction::addHook($name,$hook,$priority);
    }

    /**
     * 执行钩子
     * @param $name
     * @param array $parameter
     */
    public function doHook($name,$parameter = array()){
        HookAction::doHook($name,$parameter);
    }
}