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
     * @param $mode
     * @return mixed
     * @throws \Exception
     */
    public function getConnect($mode)
    {
        if(!isset($mode)) throw new \Exception('数据库操作模式没有明确');
        if(!isset($this->_connect[$mode])) throw new \Exception('没有配置操作模式为'.$mode.'的数据库配置');
        return $this->_connect[$mode];
    }

    /**
     * @param $mode
     * @param $connect
     */
    public function setConnect($mode,$connect)
    {
        $this->_connect[$mode] = $connect;
    }

    /**
     * @param null $mode
     * @return mixed
     */
    public function getConfig($mode = null)
    {
        return isset($mode) ? $this->_config[$mode] : $this->_config;
    }

    /**
     * @param $mode
     * @param $config
     */
    public function setConfig($mode,$config)
    {
        $this->_config[$mode] = $config;
    }

    /**
     * Db constructor.
     */
    public function __construct()
    {
        $this->loadConfig();
        $temp = array();
        foreach($this->_config as $mode => $config){
            if(!isset($temp[$config[SPHP_DB_ID]])){
                $connect = new Connect($config);
                $temp[$config[SPHP_DB_ID]] = $connect->factory();
            }
            $this->_connect[$mode] = $temp[$config['id']];
        }
        unset($temp);
    }


    /**
     * @param $sql
     * @param string $mode
     * @return mixed
     * @throws \Exception
     */
    public function exec($sql, $mode = SPHP_DB_WRITE){
        return $this->getConnect($mode)->exec($sql);
    }

    /**
     * @return $this
     */
    public function transaction() {
        $this->getConnect(SPHP_DB_WRITE)->startTransaction();
        return $this;
    }

    /**
     * @return $this
     */
    public function commit() {
        $this->getConnect(SPHP_DB_WRITE)->commit();
        return $this;
    }

    /**
     * @return $this
     */
    public function rollback() {
        $this->getConnect(SPHP_DB_WRITE)->rollback();
        return $this;
    }

    /**
     * @return mixed
     */
    public function lastInsertId(){
        return $this->getConnect(SPHP_DB_WRITE)->lastInsertId();
    }


    /**
     * @throws \Exception
     */
    private function loadConfig(){
        $configs = Config::getValue(SPHP_DB);
        if(empty($configs) || !is_array($configs)){
            throw new \Exception("数据库配置没有配置或配置不正确");
        }
        $modes = $priority = array();
        foreach($configs as $config){
            isset($config[SPHP_DB_MODE]) || $config[SPHP_DB_MODE] = [SPHP_DB_WRITE,SPHP_DB_READ];
            isset($config[SPHP_DB_PRI]) || $config[SPHP_DB_PRI] = 1;
            foreach($config[SPHP_DB_MODE] as $mode){
                $modes[$mode][$config[SPHP_DB_PRI]] = $config;
                $priority[$mode][] = $config[SPHP_DB_PRI];
            }
        }
        foreach($priority as $mode => $pris){
            $this->_config[$mode] = $modes[$mode][$this->priority($pris)];
        }
    }

    private function priority($proArr){
        $result = 0;
        $proSum = array_sum($proArr);
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $proCur;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        return $result;
    }

}