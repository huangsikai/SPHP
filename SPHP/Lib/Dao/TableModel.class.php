<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/13
 */

namespace SPHPCore\Lib\Dao;


use SPHPCore\Lib\HookAction;

class TableModel extends Table
{
    private $_prototype;

    /**
     * @param $prototype
     */
    public function setPrototype($prototype){
        $this->_prototype = $prototype;
        unset($prototype);
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function insert($data)
    {
        $result = false;
        if(false !== $this->_prototype->beforeInsert($data)){
            $result = parent::insert($data);
            if($result)
                $this->_prototype->afterInsert($data,$result);
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
        if(false !== $this->_prototype->beforeUpdate($data)){
            $result = parent::update($data, $primary);
            if($result)
                $this->_prototype->afterUpdate($data);
        }
        return $result;
    }

    /**
     * @param null $primary
     * @return mixed
     */
    public function find($primary = null){
        if(!empty($primary) && !empty($this->primaryKey))
            $this->expression->setOptions(Expression::EXPLAIN_WHERE,array(array($this->primaryKey => $primary)));
        $this->expression->setOptions(Expression::EXPLAIN_LIMIT,array(1));
        $this->expression->select();
        $execute = $this->db->getConnect()->execute($this->expression->getExpres(),$this->expression->getParameter());
        $statement = $execute->getStatement();
        if($row = $execute->fetchRow()){
            $this->_prototype->setData($row);
            $this->_prototype->exchangeArray($row);
            $this->_prototype->setStatement($statement);
            HookAction::doHook('model',array(get_class($this->_prototype),$row));
        }
        return $this->_prototype;
    }

    /**
     * @return array|DataObject
     */
    public function findAll(){
        $this->expression->select();
        $execute = $this->db->getConnect()->execute($this->expression->getExpres(),$this->expression->getParameter());
        $statement = $execute->getStatement();
        $result = new ModelObject();
        while($row = $execute->fetchRow()){
            $prototype = clone $this->_prototype;
            $prototype->setData($row);
            $prototype->exchangeArray($row);
            $prototype->setStatement($statement);
            $result[] = $prototype;
            HookAction::doHook('model',array(get_class($prototype),$row));
        }
        unset($execute,$statement);
        return $result;
    }
}