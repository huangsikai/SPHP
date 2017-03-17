<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/13
 */

namespace SPHPCore\Lib\Mvc;



use SPHPCore\Lib\Dao\ModelData;
use SPHPCore\Lib\Dao\Relation\HasMany;
use SPHPCore\Lib\Dao\Relation\HasOne;
use SPHPCore\Lib\HookAction;

abstract class Model extends ModelData
{

    protected static $tableName;
    protected static $primaryKey;

    /**
     * @return string
     */
    public static function _tableName()
    {
        if(!empty(static::$tableName)){
            return static::$tableName;
        }else{
            $ref = new \ReflectionClass(get_called_class());
            return $ref->getShortName();
        }
    }

    /**
     * @return string
     */
    public static function _primaryKey()
    {
        return !empty(static::$primaryKey) ? static::$primaryKey : 'id';
    }

    /**
     * Model constructor.
     * @param array $data
     */
    final public function __construct($data = array()){
        parent::__construct($data);
        $this->_init();
    }


    /**
     * @return mixed
     */
    public static function tb()
    {
        return self::getTableModel();
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
    public function _init(){}

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

    public function hasOne($name, $model, $foreignKey, $primaryKey = ''){
        if(is_subclass_of($model,__CLASS__)){
            $primaryKey = $primaryKey ?: $this::_primaryKey();
            $this->setRelation(new HasOne($this,$model,$foreignKey,$primaryKey,$name));
        }
    }

    public function hasMany($name, $model, $foreignKey, $primaryKey = ''){
        if(is_subclass_of($model,__CLASS__)){
            $primaryKey = $primaryKey ?: $this::_primaryKey();
            $this->setRelation(new HasMany($this,$model,$foreignKey,$primaryKey,$name));
        }
    }

    public function belongsTo($model,$foreignKey,$primaryKey,$alias){

    }


}