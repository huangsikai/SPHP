<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/13
 */

namespace SPHPCore\Lib\Mvc;



use SPHPCore\Lib\Dao\ModelData;
use SPHPCore\Lib\HookAction;

abstract class Model extends ModelData
{

    protected static $tableName;
    protected static $primaryKey;

    /**
     * @return string
     */
    protected function __tableName()
    {
        if(!empty($this::$tableName)){
            return $this::$tableName;
        }else{
            $ref = new \ReflectionObject($this);
            return $ref->getShortName();
        }
    }

    /**
     * @return string
     */
    protected function __primaryKey()
    {
        if(!empty($this::$primaryKey)){
            return $this::$primaryKey;
        }else{
            return 'id';
        }
    }


    /**
     * Model constructor.
     */
    final public function __construct(){
        parent::__construct();
        $this->__init();
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

    /**
     * 初始化方法 给子控制器重写
     */
    public function __init(){}

    /**
     * 添加数据前
     * @param $data
     */
    public function beforeInsert(&$data){}

    /**
     * 添加数据后
     * @param $data
     * @param $result
     */
    public function afterInsert($data, $result){}

    /**
     * 修改数据前
     * @param $data
     */
    public function beforeUpdate(&$data){}

    /**
     * 求改数据后
     * @param $data
     */
    public function afterUpdate($data){}


}