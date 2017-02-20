<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/7
 */

namespace SPHPCore\Lib\Dao\Drives;


use SPHPCore\Lib\Dao\Drives;

class Mysql extends Drives
{
    private $_connector = null;
    private $_statement = null;


    /**
     * 是否自動提交
     *
     * @var bool
     */
    private $_is_auto_commit = true;
    /**
     * @var array
     */
    private $_history = array();

    public function __construct($config){
        try {
            $this->_connector = new \PDO('mysql:dbname='.$config[SPHP_DB_DATABASE].';host='.$config[SPHP_DB_HOST], $config[SPHP_DB_USER], $config[SPHP_DB_PWD], array(
//                \PDO::ATTR_CASE				=> \PDO::CASE_LOWER,
                \PDO::ATTR_ORACLE_NULLS		=> \PDO::NULL_TO_STRING,
                \PDO::ATTR_STRINGIFY_FETCHES	=> false,
                \PDO::ATTR_AUTOCOMMIT => true,
//              \PDO::ATTR_EMULATE_PREPARES => false,  //是否预处理模拟
//		        \PDO::ATTR_PERSISTENT		=> true,   //持久化链接
            ));
            $this->_connector->query('set names '.$config[SPHP_DB_CHARSET]);
            $errMode = \PDO::ERRMODE_SILENT;
            if(DEBUG){
                $errMode = \PDO::ERRMODE_EXCEPTION;
            }
            $this->_connector->setAttribute(\PDO::ATTR_ERRMODE, $errMode);
        }catch(SPHPException $e) {
        }
    }


    public function startTransaction() {
        if ($this->_is_auto_commit === true) {
            $this->_connector->beginTransaction();
            $this->_is_auto_commit = false;
        }
        return $this;
    }

    public function commit() {
        if ($this->_is_auto_commit === false) {
            $this->_connector->commit();
            $this->_is_auto_commit = true;
        }
        return $this;
    }

    public function rollback() {
        if ($this->_is_auto_commit === false) {
            $this->_connector->rollBack();
            $this->_is_auto_commit = true;
        }
        return $this;
    }


    public function history($index = 0) {
        if ($index < 0) return $this->_history;
        $index = count($this->_history) - $index - 1;
        if ($index < 0) return null;
        return isset($this->_history[$index]) ? $this->_history[$index] : null;
    }

    public function execute($sql,$params = array()) {
        $this->_history[] = array($sql, $params);
        try {
            $this->_statement = $this->_connector->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
            if (empty($params))
                $this->_statement->execute();
            else
                $this->_statement->execute($params);
            return $this;

        }catch(SPHPException $e){

        }
    }



    /**
     * 受影响行数
     * @return int
     */
    public function rowCount(){
        return $this->_statement->rowCount();
    }

    /**
     * 返回结果
     * @return bool
     */
    public function fetchRow() {
        if ($this->rowCount() > 0)
            return $this->_statement->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT);
        else
            return false;
    }

    public function getStatement(){
        return $this->_statement;
    }

    public function lastInsertId() {
        return $this->_connector->lastInsertId();
    }


    public function errorCode() {
        return $this->_statement->errorCode();
    }

    public function errorInfo() {
        return $this->_statement->errorInfo();
    }
}