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

    protected $where;
    protected $orderby;
    protected $limit;

    /**
     * @param mixed $where
     */
    public function setWhere($where)
    {
        $this->where = $where;
    }

    /**
     * @param mixed $orderby
     */
    public function setOrderby($orderby)
    {
        $this->orderby = $orderby;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getCollection(){
        if(empty($this->collection)){
            $model = $this->modelName;
            $tb = $model::tb()->where(array($this->foreignKey => $this->parent[$this->primaryKey]));
            if(!empty($this->where))
                $tb->where($this->where);
            if(!empty($this->orderby))
                $tb->orderby($this->orderby);
            if(!empty($this->limit))
                $tb->limit($this->limit);
            $this->collection = $tb->findAll();
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