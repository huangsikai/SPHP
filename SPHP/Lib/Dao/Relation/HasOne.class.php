<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/3/14
 */

namespace SPHPCore\Lib\Dao\Relation;



use SPHPCore\Lib\Dao\Relation;

class HasOne extends Relation
{
    protected $model;

    /**
     * @return mixed
     */
    public function getModel(){
        if(empty($this->model)){
            $model = $this->modelName;
            $this->model = $model::tb()->where(array($this->foreignKey => $this->parent[$this->primaryKey]))->find();
        }
        return $this->model;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->getModel(),$name),$arguments);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $model = $this->getModel();
        return $model->isNew() ? '' : $model->$name;
    }



    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $model = $this->getModel();
        $model->isNew() ?: $model->$name = $value;
    }
}