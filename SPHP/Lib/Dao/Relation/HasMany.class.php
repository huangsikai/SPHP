<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/3/17
 */

namespace SPHPCore\Lib\Dao\Relation;



use SPHPCore\Lib\Dao\Relation;

class HasMany extends Relation
{
    protected $collection;


    /**
     * @return mixed
     */
    public function getCollection(){
        if(empty($this->collection)){
            $model = $this->modelName;
            $this->collection = $model::tb()->where(array($this->foreignKey => $this->parent[$this->primaryKey]))->findAll();
        }
        return $this->collection;
    }
    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->getCollection(),$name),$arguments);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $collection = $this->getCollection();
        return $collection->isEmpty() ? '' : $collection->$name;
    }

    /**
     * @param callable $callback
     */
    public function each(callable $callback){
        $array = $this->getCollection();
        foreach ($array as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
    }
}