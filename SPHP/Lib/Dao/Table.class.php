<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/7
 */

namespace SPHPCore\Lib\Dao;


class Table
{
    protected 
        $db,
        $expression,
        $tableName,
        $primaryKey;

    /**
     * Table constructor.
     */
    public function __construct()
    {
        $this->db = $GLOBALS[SPHP_ACTION]->getDb();
        $this->expression = Expression::instance($this,$this->db->getConfig());
    }


    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @param mixed $primaryKey
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        $this->expression->setOptions($name,$arguments);
        return $this;
    }

    /**
     * @return $this
     */
    public function transaction() {
        $this->db->transaction();
        return $this;
    }

    /**
     * @return $this
     */
    public function commit() {
        $this->db->commit();
        return $this;
    }

    /**
     * @return $this
     */
    public function rollback() {
        $this->db->rollback();
        return $this;
    }

    /**
     * @return mixed
     */
    public function lastInsertId(){
        return $this->db->lastInsertId();
    }


    /**
     * @param $data
     * @return bool|mixed
     */
    public function insert($data){
        $result = false;
        if(!empty($data) && is_array($data)){
            $this->expression->insert($data);
            $execute = $this->db->getConnect()->execute($this->expression->getExpres(),$this->expression->getParameter());
            $result = $execute->rowCount();
            if($result){
                $lastId = $this->lastInsertId();
                $result =  !empty($lastId) ? $lastId : $result;
            }
        }
        unset($data);
        return $result;
    }


    /**
     * @param null $primary
     * @return mixed
     */
    public function delete($primary = null){
        if(!empty($primary) && !empty($this->primaryKey))
            $this->expression->setOptions(Expression::EXPLAIN_WHERE,array(array($this->primaryKey => $primary)));
        $this->expression->delete();
        $execute = $this->db->getConnect()->execute($this->expression->getExpres(),$this->expression->getParameter());
        return $execute->rowCount();
    }


    /**
     * @param $data
     * @param null $primary
     * @return mixed
     */
    public function update($data, $primary = null){
        if(!empty($primary) && !empty($this->primaryKey))
            $this->expression->setOptions(Expression::EXPLAIN_WHERE,array(array($this->primaryKey => $primary)));
        $this->expression->update($data);
        $execute = $this->db->getConnect()->execute($this->expression->getExpres(),$this->expression->getParameter());
        return $execute->rowCount();
    }


    /**
     * @param null $primary
     * @return array
     */
    public function find($primary = null){
        if(!empty($primary) && !empty($this->primaryKey))
            $this->expression->setOptions(Expression::EXPLAIN_WHERE,array(array($this->primaryKey => $primary)));
        $this->expression->setOptions(Expression::EXPLAIN_LIMIT,array(1));
        $this->expression->select();
        $execute = $this->db->getConnect()->execute($this->expression->getExpres(),$this->expression->getParameter());
        if($row = $execute->fetchRow()){
            return $row;
        }else{
            return array();
        }
    }

    /**
     * @return array
     */
    public function findAll(){
        $this->expression->select();
        $execute = $this->db->getConnect()->execute($this->expression->getExpres(),$this->expression->getParameter());
        $rows = array();
        while($row = $execute->fetchRow()){
            $rows[] = $row;
        }
        return $rows;
    }

}