<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/3/15
 */

namespace SPHPCore\Lib\Dao\Relation;


use SPHPCore\Lib\Dao\Relation;

class BelongsTo extends Relation
{
    protected $model;

    /**
     * @return mixed
     */
    public function getModel(){
        if(empty($this->model)){
            $model = $this->modelName;
            $this->model = $model::tb()->where(array($this->primaryKey => $this->parent[$this->foreignKey]))->find();
        }
        return $this->model;
    }

}