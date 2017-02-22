<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/7
 */

namespace SPHPCore\Lib\Dao\Expression;



abstract class Base
{

    const
        EXPLAIN_FIELD = 'field',
        EXPLAIN_ALIAS = 'alias',
        EXPLAIN_WHERE = 'where',
        EXPLAIN_LIMIT = 'limit',
        EXPLAIN_ORDER_BY = 'orderby',
        EXPLAIN_GROUP_BY = 'groupby',
        EXPLAIN_JOIN = 'join';

    const
        TAG_JOIN_TYPE = 'type';


    protected
        $table = '',
        $expres = '',
        $options = array(),
        $parameter = array(),
        $config = array();


    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }


    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getExpres()
    {
        return $this->expres;
    }


    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * @param array $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter[] = $parameter;
    }

    /**
     *
     */
    protected function reSet(){
        $this->expres = '';
        $this->parameter = array();
    }

    /**
     * @return mixed
     */
    abstract protected function field();

    /**
     * @return mixed
     */
    abstract protected function alias();

    /**
     * @return mixed
     */
    abstract protected function where();


    /**
     * @return mixed
     */
    abstract protected function orderby();

    /**
     * @return mixed
     */
    abstract protected function groupby();

    /**
     * @return mixed
     */
    abstract protected function join();

    /**
     * @return mixed
     */
    abstract protected function limit();


    /**
     * @return mixed
     */
    abstract public function select();

    /**
     * @param $data
     * @return mixed
     */
    abstract public function insert($data);

    /**
     * @param $data
     * @return mixed
     */
    abstract public function update($data);

    /**
     * @return mixed
     */
    abstract public function delete();


}




