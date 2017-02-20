<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore\Lib;


use SPHPCore\Lib\Request\Parameter;
use SPHPCore\Lib\Request\Session;

class Request
{
    private $_action;
    private $_data;
    private $_parameterInstance;
    private $_sessionInstance;
    private $_currentControllerName;
    private $_currentActionName;

    /**
     * @return mixed
     */
    public function getParameterInstance()
    {
        if(!isset($this->_parameterInstance) ||
            !($this->_parameterInstance instanceof \SPHPCore\Lib\Request\Parameter))
        {
            $this->_parameterInstance = new Parameter();
        }
        return $this->_parameterInstance;
    }

    /**
     * @return mixed
     */
    public function getSessionInstance()
    {
        if(!isset($this->_sessionInstance) ||
            !($this->_sessionInstance instanceof \SPHPCore\Lib\Request\Session))
        {
            $this->_sessionInstance = new Session();
        }
        return $this->_sessionInstance;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getSession($key)
    {
        return $this->getSessionInstance()->get($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setSession($key, $value)
    {
        $this->getSessionInstance()->set($key,$value);
    }

    /**
     * 获取get参数
     * @param string $index
     * @param string $default
     * @param null $filter
     * @return mixed
     */
    public function get($index = '', $default = '', $filter = null){
        return $this->getParameterInstance()->get($index, $default, $filter);
    }

    /**
     * 获取post参数
     * @param string $index
     * @param string $default
     * @param null $filter
     * @return mixed
     */
    public function post($index = '', $default = '', $filter = null){
        return $this->getParameterInstance()->post($index, $default, $filter);
    }

    /**
     * 是否post提交
     * @param $index
     * @return mixed
     */
    public function isPost($index = null){
        return $this->getParameterInstance()->isPost($index);
    }

    /**
     * 获取post或get数据
     * @param string $index
     * @param string $default
     * @param null $filter
     * @return mixed
     */
    public function request($index = '', $default = '', $filter = null){
        return $this->getParameterInstance()->isPost($index) ? $this->getParameterInstance()->post($index, $default, $filter) : $this->getParameterInstance()->get($index, $default, $filter);
    }

    /**
     * 是否ajax提交
     * @return mixed
     */
    public function isAjax(){
        return $this->getParameterInstance()->isAjax();
    }

    /**
     * 是否ssl
     * @return mixed
     */
    public function isSsl(){
        return $this->getParameterInstance()->isSsl();
    }


    /**
     * 获取当前控制器方法名称
     * @return mixed
     */
    public function getCurrentActionName()
    {
        return $this->_currentActionName;
    }

    /**
     * @param mixed $currentActionName
     */
    public function setCurrentActionName($currentActionName)
    {
        $this->_currentActionName = $currentActionName;
    }

    /**
     * 获取当前控制器名称
     * @return mixed
     */
    public function getCurrentControllerName()
    {
        return $this->_currentControllerName;
    }

    /**
     * @param mixed $currentControllerName
     */
    public function setCurrentControllerName($currentControllerName)
    {
        $this->_currentControllerName = $currentControllerName;
    }



    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->_action = $GLOBALS[SPHP_ACTION];
        $route = new Route();
        $route->resolve($this);
    }

    /**
     * @return null
     */
    public function getRequestPath(){
        return isset($this->_data['request_path']) ? $this->_data['request_path'] : null;
    }

    /**
     * @param mixed $request_path
     */
    public function setRequestPath($request_path){
        $this->_data['request_path'] = $request_path;
    }

    /**
     * @return mixed
     */
    public function getControllerName(){
        return !empty($this->_data[SPHP_ROUTE_CONTROLLER]) ? $this->_data[SPHP_ROUTE_CONTROLLER]  : 'Index';
    }

    /**
     * @param mixed $controller
     */
    public function setControllerName($controller){
        $this->_data[SPHP_ROUTE_CONTROLLER] = $controller;
    }

    /**
     * @return mixed
     */
    public function getActionName(){
        return !empty($this->_data[SPHP_ROUTE_ACTION]) ? $this->_data[SPHP_ROUTE_ACTION]  : 'index';
    }

    /**
     * @param mixed $action
     */
    public function setActionName($action){
        $this->_data[SPHP_ROUTE_ACTION] = $action;
    }


    /**
     * @return mixed
     */
    public function getTarget(){
        return isset($this->_data[SPHP_ROUTE_TARGET]) ? $this->_data[SPHP_ROUTE_TARGET] : null;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target){
        $this->_data[SPHP_ROUTE_TARGET] = $target;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key,$value){
        $this->_data[$key] = $value;
    }

    /**
     * @param $key
     * @return null
     */
    public function __get($key){
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

}