<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/13
 */

namespace SPHPCore\Lib\Dao;


use SPHPCore\Lib\Dao\Expression\Base;
use SPHPCore\Lib\HookAction;
use SPHPCore\Lib\Mvc\Model;

class TableModel extends Table
{
    private $_prototype;

    public function __construct($model)
    {
        parent::__construct();
        $this->setTableName($model::__tableName());
        $this->setPrimaryKey($model::__primaryKey());
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getPrototype()
    {
        if(empty($this->_prototype))
            $this->_prototype = new $this->model();
        return $this->_prototype;
    }

    /**
     * @param mixed $prototype
     */
    public function setPrototype($prototype)
    {
        $this->_prototype = $prototype;
    }


    /**
     * @param $data
     * @return bool|mixed
     */
    public function insert($data)
    {
        $result = false;
        if(false !== $this->getPrototype()->beforeInsert($data)){
            $result = parent::insert($data);
            if($result)
                $this->getPrototype()->afterInsert($data,$result);
        }
        return $result;
    }

    /**
     * @param $data
     * @param null $primary
     * @return bool|mixed
     */
    public function update($data, $primary = null)
    {
        $result = false;
        if(false !== $this->getPrototype()->beforeUpdate($data)){
            $result = parent::update($data, $primary);
            if($result)
                $this->getPrototype()->afterUpdate($data);
        }
        return $result;
    }

    /**
     * @param null $primary
     * @return mixed
     */
    public function find($primary = null){
        if(!empty($primary) && !empty($this->primaryKey))
            $this->expression->setOptions(Base::EXPLAIN_WHERE,array(array($this->primaryKey => $primary)));
        $this->expression->setOptions(Base::EXPLAIN_LIMIT,array(1));
        $this->expression->select();
        $execute = $this->db->getConnect(SPHP_DB_READ)->execute($this->expression->getExpres(),$this->expression->getParameter());
        $statement = $execute->getStatement();
        if($row = $execute->fetchRow()){
            $prototype = new $this->model($row);
            $prototype->setStatement($statement);
            HookAction::doHook('model',array($prototype));
        }else{
            $prototype = new $this->model();
        }
        unset($execute,$statement);
        return $prototype;
    }

    /**
     * @return array|ModelObject
     */
    public function findAll(){
        $this->expression->select();
        $execute = $this->db->getConnect(SPHP_DB_READ)->execute($this->expression->getExpres(),$this->expression->getParameter());
        $statement = $execute->getStatement();
        $result = new ModelObject();
        while($row = $execute->fetchRow()){
            $prototype = new $this->model($row);
            $prototype->setStatement($statement);
            $result[] = $prototype;
            HookAction::doHook('model',array($prototype));
            unset($prototype);
        }
        unset($execute,$statement);
        return $result;
    }
}