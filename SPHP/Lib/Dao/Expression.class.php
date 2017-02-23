<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/7
 */

namespace SPHPCore\Lib\Dao;



class Expression
{

    protected
        $expres = '',
        $options = array(),
        $parameter = array(),
        $expression = array();

    /**
     * Expression constructor.
     * @param $table
     * @param $configs
     * @throws \Exception
     */
    public function __construct($table, $configs)
    {
        foreach($configs as $mode => $config){
            $dbType = ucfirst(strtolower($config[SPHP_DB_TYPE]));
            $className = '\\SPHPCore\\Lib\\Dao\\Expression\\'.$dbType;
            if(class_exists($className) &&
                is_subclass_of($className,'\\SPHPCore\\Lib\\Dao\\Expression\\Base'))
            {
                $this->expression[$mode] = new $className();
                $this->expression[$mode]->setTable($table);
                $this->expression[$mode]->setConfig($config);
            }else
            {
                throw new \Exception("数据库类型配置不正确");
            }
        }
        unset($table,$configs);
    }

    /**
     * @return string
     */
    public function getExpres()
    {
        return $this->expres;
    }

    /**
     * @param array $options
     */
    public function setOptions($index,$options)
    {
        if(!empty($index)){
            if(isset($this->options[$index])){
                $this->options[$index] = array_merge($this->options[$index],$options);
            }else{
                $this->options[$index]= $options;
            }
        }
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param $mode
     * @return mixed
     * @throws \Exception
     */
    public function getExpression($mode)
    {
        if(!isset($mode)) throw new \Exception('数据库操作模式没有明确');
        if(!isset($this->expression[$mode])) throw new \Exception('没有配置操作模式为'.$mode.'的数据库配置');
        return $this->expression[$mode];
    }


    protected function reSet()
    {
        $this->expres = '';
        $this->parameter = array();
    }

    public function select()
    {
        $this->reSet();
        $this->getExpression(SPHP_DB_READ)->setOptions($this->options);
        $this->getExpression(SPHP_DB_READ)->select();
        $this->expres = $this->getExpression(SPHP_DB_READ)->getExpres();
        $this->parameter = $this->getExpression(SPHP_DB_READ)->getParameter();
        $this->options = array();
    }

    public function insert($data)
    {
        $this->reSet();
        $this->getExpression(SPHP_DB_WRITE)->insert($data);
        $this->expres = $this->getExpression(SPHP_DB_WRITE)->getExpres();
        $this->parameter = $this->getExpression(SPHP_DB_WRITE)->getParameter();
        $this->options = array();
    }

    public function update($data)
    {
        $this->reSet();
        $this->getExpression(SPHP_DB_WRITE)->setOptions($this->options);
        $this->getExpression(SPHP_DB_WRITE)->update($data);
        $this->expres = $this->getExpression(SPHP_DB_WRITE)->getExpres();
        $this->parameter = $this->getExpression(SPHP_DB_WRITE)->getParameter();
        $this->options = array();
    }

    public function delete()
    {
        $this->reSet();
        $this->getExpression(SPHP_DB_WRITE)->setOptions($this->options);
        $this->getExpression(SPHP_DB_WRITE)->delete();
        $this->expres = $this->getExpression(SPHP_DB_WRITE)->getExpres();
        $this->parameter = $this->getExpression(SPHP_DB_WRITE)->getParameter();
        $this->options = array();
    }

}




