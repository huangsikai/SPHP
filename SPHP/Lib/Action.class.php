<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore\Lib;


use SPHPCore\Lib\Dao\Db;
use SPHPCore\Lib\Dao\Table;
use SPHPCore\Lib\Mvc\View;

class Action {

    private
        $_request,
        $_response,
        $_controller,
        $_view,
        $_assign,
        $_db;

    public function __construct(){}

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->_response = $response;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        if(!isset($this->_view) || !($this->_view instanceof \SPHPCore\Lib\Mvc\View)){
            $this->_view = new View();
        }
        return $this->_view;
    }

    /**
     * @param mixed $view
     */
    public function setView($view)
    {
        if($this->_view instanceof \SPHPCore\Lib\Mvc\View)
            $this->_view = $view;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    /**
     * @param $index
     * @return null
     */
    public function getAssign($index)
    {
        return isset($this->_assign[$index]) ? $this->_assign[$index] : null;
    }

    /**
     * @param $index
     * @param $value
     */
    public function setAssign($index,$value = null)
    {
        !empty($index) ? $this->_assign[$index] = $value : true;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        if(!isset($this->_db) || !($this->_db instanceof \SPHPCore\Lib\Dao\Db)){
            $this->_db = new Db();
        }
        return $this->_db;
    }

    /**
     * @param $tableName
     * @param $primary
     * @return Table
     */
    public function getTable($tableName, $primary){
        $table = new Table();
        $table->setTableName($tableName);
        $table->setPrimaryKey($primary);
        return $table;
    }



    /**
     * @throws \Exception
     */
    public function dispatch(){
        $this->_request = new Request();
        $this->_response = new Response();
        $this->call($this->_request->getControllerName(),$this->_request->getActionName());
    }

    /**
     * @param $controllerName
     * @param $actionName
     * @throws \Exception
     */
    public function call($controllerName,$actionName){
        $this->_request->setCurrentControllerName($controllerName);
        $this->_request->setCurrentActionName($actionName);
        $controllerClass = APP_NAME.'\\'.MODULE_DIR.'\\'.MODULE.'\\'.CONTROLLER_DIR.'\\'.$controllerName;
        if(class_exists($controllerClass)){
            HookAction::doHook('controller',array($controllerName,$actionName));
            $this->_controller = new $controllerClass($this->_request,$this->_response);
            $this->_controller->__init();
            if(method_exists($this->_controller,$actionName)){
                $this->_controller->$actionName();
            }else{
                $this->_controller->unknown();
            }
        }else{
            throw new \Exception("控制器【".$controllerClass."】不存在");
        }
        unset($controllerName,$actionName,$controllerClass);
    }
}