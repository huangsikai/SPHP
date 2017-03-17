<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/3/13
 */

namespace SPHPCore\Lib\Dao;


use SPHPCore\Lib\Dao\Relation\HasOne;

abstract class Relation implements \ArrayAccess
{

    protected $parent;
    protected $modelName;
    protected $foreignKey;
    protected $primaryKey;
    protected $name;

    public function __construct($parent,$modelName,$foreignKey,$primaryKey,$name)
    {
        $this->parent = $parent;
        $this->modelName = $modelName;
        $this->foreignKey = $foreignKey;
        $this->primaryKey = $primaryKey;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return mixed
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }


    public static function notifyRelation($relations,$method){
        $result = true;
        if(!empty($relations)){
            foreach($relations as $relation){
                switch($method){
                    case 'save':
                    case 'delete':
                        ($relation instanceof HasOne) &&  $result = $result && $relation->$method()!==false;
                        break;
                }

            }
        }
        return $result;
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

}