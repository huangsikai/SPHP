<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/7
 */

namespace SPHPCore\Lib\Dao;


abstract class Drives
{
    /**
     * 开启事务
     * @return mixed
     */
    abstract public function startTransaction();

    /**
     * 提交事务
     * @return mixed
     */
    abstract public function commit();

    /**
     * 事务回滚
     * @return mixed
     */
    abstract public function rollback();

    /**
     * sql执行
     * @param $sql
     * @param array $params
     * @return mixed
     */
    abstract public function execute($sql,$params = array());

    /**
     * 自定义sql
     * @param $sql
     * @return mixed
     */
    abstract public function exec($sql);

    /**
     * 受影响行数
     * @return int
     */
    abstract public function rowCount();

    /**
     * 返回结果
     * @return mixed
     */
    abstract public function fetchRow();

    /**
     * 最后插入id
     * @return mixed
     */
    abstract public function lastInsertId();

    /**
     * 某个sql执行的唯一声明
     * @return mixed
     */
    abstract function getStatement();

    /**
     * 错误码
     * @return mixed
     */
    abstract public function errorCode();

    /**
     * 错误信息
     * @return mixed
     */
    abstract public function errorInfo();
}