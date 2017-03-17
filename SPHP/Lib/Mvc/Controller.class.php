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
    private $_dispatch;
    protected $request;
    protected $response;

    /**
     * 转发
     * @param $controllerName
     * @param $actionName
     */
    protected function forward($controllerName,$actionName){
        $this->_dispatch->call($controllerName,$actionName);
    }

    /**
     * 传递视图参数
     * @param $index
     * @param $value
     */
    protected function assign($index,$value){
        $this->_dispatch->getView()->setData($index,$value);
    }

    /**
     * 初始化方法 给子控制器重写
     */
    protected function _init(){}

    /**
     * Controller constructor.
     * @param $
     */
    final public function __construct(){
        $this->_dispatch = $GLOBALS[SPHP_DISPATCH];
        $this->request = $GLOBALS[SPHP_DISPATCH]->getRequest();
        $this->response = $GLOBALS[SPHP_DISPATCH]->getResponse();
        $this->_init();
    }


    /**
     * 未知方法 给子控制器重写
     */
    public function unknown(){}

    /**
     * 设置布局名称
     * @param $layout
     */
    public function setLayout($layout){
        $this->_dispatch->getView()->setLayout($layout);
    }

    /**
     * 获取数据库
     * @return mixed
     */
    public function db(){
       return $this->_dispatch->getDb();
    }

    /**
     * 获取表对象
     * @param $tableName
     * @param string $primary
     * @return mixed
     */
    public function tb($tableName, $primary = "id"){
        return $this->_dispatch->getTable($tableName, $primary);
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