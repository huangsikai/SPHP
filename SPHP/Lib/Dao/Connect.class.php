<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/6
 */

namespace SPHPCore\Lib\Dao;



class Connect
{
    protected $driveType = array(
        'mysql'=>'SPHPCore\Lib\Dao\Drives\Mysql',
        'sqlite'=>'SPHPCore\Lib\Dao\Drives\SQLite'
    );
    protected $config;
    public function __construct($config)
    {
        if(empty($config)){
            throw new \Exception("数据库配置没有配置或配置不正确");
        }

        if(isset($this->driveType[strtolower($config['type'])])){
            $this->config = $config;unset($config);
        }else{
            throw new \Exception("数据库类型配置不正确");
        }
    }


    public function factory(){
        return new $this->driveType[strtolower($this->config['type'])]($this->config);
    }
}