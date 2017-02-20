<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/6
 */

namespace SPHPCore\Lib\Mvc;


use SPHPCore\Common;
use SPHPCore\Lib\Config;
use SPHPCore\Lib\HookAction;

class View
{
    private
        $_action,
        $_layout,
        $_viewName;

    /**
     * @return string
     */
    private function getDefaultView(){
        return $this->_action->getRequest()->getCurrentControllerName().'/'.$this->_action->getRequest()->getCurrentActionName();
    }

    /**
     * @param $viewName
     * @return string
     */
    private function getFullPath($viewName){
        $tmpSuffix = Config::getValue(SPHP_TPL_SUFFIX);
        return BASE_PATH.PATH_OS_SLASH.APP_NAME.PATH_OS_SLASH.MODULE_DIR.PATH_OS_SLASH.MODULE.PATH_OS_SLASH.VIEW_DIR.PATH_OS_SLASH.$viewName.$tmpSuffix;
    }

    /**
     * 当前控制器名称
     * @return mixed
     */
    public function getControllerName(){
        return $this->_action->getRequest()->getCurrentControllerName();
    }

    /**
     * 当前方法名称
     * @return mixed
     */
    public function getActionName(){
        return $this->_action->getRequest()->getCurrentActionName();
    }

    /**
     * @return mixed
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * @param mixed $layout
     */
    public function setLayout($layout)
    {
        $this->_layout = $layout;
    }

    /**
     * @return mixed
     */
    public function getViewName()
    {
        return $this->_viewName;
    }

    /**
     * @param mixed $viewName
     */
    public function setViewName($viewName)
    {
        $this->_viewName = $viewName;
    }

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->_action = $GLOBALS[SPHP_ACTION];
    }

    /**
     * @throws \Exception
     */
    public function display()
    {
        if(!empty($this->_layout)){
            $this->layout();
        }else{
            $this->content();
        }
    }

    /**
     * 加载模版
     */
    public function content()
    {
        if(empty($this->_viewName)){
            $this->_viewName = $this->getDefaultView();
        }
        HookAction::doHook('template',array($this->_viewName));
        $viewPath = $this->getFullPath($this->_viewName);
        if(!is_file($viewPath)) throw new \Exception("视图不存在：$viewPath");
        include_once $viewPath;
    }

    /**
     * 加载布局
     */
    public function layout()
    {
        HookAction::doHook('layout',array($this->_layout));
        $layoutPath = $this->getFullPath($this->_layout);
        if(!is_file($layoutPath)) throw new \Exception("布局不存在：$layoutPath");
        include_once $layoutPath;
    }

    /**
     * 记载html
     * @param $htmlName
     * @throws \Exception
     */
    public function html($htmlName)
    {
        $path = $this->getFullPath($htmlName);
        if(!is_file($path)) throw new \Exception("Html文件不存在：$path");
        include_once $path;
    }


    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return $this->_action->getAssign($name) ? $this->_action->getAssign($name) : null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->_action->setAssign($name,$value);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->_action->getAssign($name) ? true : false;
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        $this->_action->setAssign($name);
    }

    /**
     * @param $value
     * @param null $query
     * @return string
     */
    public function link($value,$query = null){
        return Common::link($value,$query);
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