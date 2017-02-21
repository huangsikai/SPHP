<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/6
 */

namespace SPHPCore\Lib\Dao;



use SPHPCore\Lib\Config;

class Db
{
    private
        $_connect,
        $_config;

    /**
     * @return mixed
     */
    public function getConnect()
    {
        return $this->_connect;
    }

    /**
     * @param mixed $connect
     */
    public function setConnect($connect)
    {
        $this->_connect = $connect;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * Db constructor.
     * @param $id
     */
    public function __construct($id = 1)
    {
        $this->loadConfig($id);
        $connect = new Connect($this->_config);
        $this->_connect = $connect->factory();
    }


    /**
     * @param $sql
     * @return mixed
     */
    public function exec($sql){
        return $this->_connect->exec($sql);
    }

    /**
     * @return $this
     */
    public function transaction() {
        $this->_connect->startTransaction();
        return $this;
    }

    /**
     * @return $this
     */
    public function commit() {
        $this->_connect->commit();
        return $this;
    }

    /**
     * @return $this
     */
    public function rollback() {
        $this->_connect->rollback();
        return $this;
    }

    /**
     * @return mixed
     */
    public function lastInsertId(){
        return $this->_connect->lastInsertId();
    }


    /**
     * @param $id
     * @throws \Exception
     */
    private function loadConfig($id){
        $configs = Config::getValue(SPHP_DB);
        if(empty($configs) || !is_array($configs)){
            throw new \Exception("数据库配置没有配置或配置不正确");
        }
        foreach($configs as $config){
            if($id == $config[SPHP_DB_ID]){
                $this->_config= $config;break;
            }
        }
    }

}