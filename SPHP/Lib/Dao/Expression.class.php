<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/7
 */

namespace SPHPCore\Lib\Dao;



abstract class Expression
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
     * @param $table
     * @param $config
     * @return Expression
     */
    public static function instance($table, $config){
        $dbType = ucfirst(strtolower($config[SPHP_DB_TYPE]));
        $className = __NAMESPACE__.'\\'.$dbType.'Expression';
        if(class_exists($className) && is_subclass_of($className,__CLASS__))
        {
            $expression = new $className;
        }else
        {
            $expression = new MysqlExpression();
        }
        $expression->config = $config;
        $expression->table = $table;
        unset($table,$config);
        return $expression;
    }

    /**
     * @return string
     */
    public function getExpres()
    {
        return $this->expres;
    }

    /**
     * @param string $expres
     */
    public function setExpres($expres)
    {
        $this->expres = $expres;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($index,$options)
    {
        if(!empty($index) && method_exists($this,$index)){
            if(isset($this->options[$index])){
                $this->options[$index] = array_merge($this->options[$index],$options);
            }else{
                $this->options[$index]= $options;
            }
        }
    }

    /**
     *
     */
    public function clearOptions(){
        $this->options = array();
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
       // $this->options = array();
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

class MysqlExpression extends Expression{

    const EXPRES_SELECT = 'SELECT';
    const EXPRES_INSERT = 'INSERT INTO';
    const EXPRES_UPDATE = 'UPDATE';
    const EXPRES_DELETE = 'DELETE';
    const EXPRES_AS = 'AS';
    const EXPRES_FROM = 'FROM';
    const EXPRES_WHERE = 'WHERE';
    const EXPRES_LIMIT = 'LIMIT';
    const EXPRES_JOIN_INNER = 'INNER';
    const EXPRES_JOIN = 'JOIN';
    const EXPRES_ON = 'ON';
    const EXPRES_AND = 'AND';
    const EXPRES_ORDER_BY = 'ORDER BY';
    const EXPRES_GROUP_BY = 'GROUP BY';




    protected function field(){
        $field = '*';
        if(!empty($this->options[self::EXPLAIN_FIELD])){
            $field = '';
            foreach($this->options[self::EXPLAIN_FIELD] as $value){
                if(is_string($value)){
                    $field .= $value.',';
                }elseif(is_array($value)){
                    $field .= implode(',',$value).',';
                }
            }
            $field = rtrim($field,',');
        }
        return $field;
    }

    /**
     * @return string
     */
    protected function alias()
    {
        $alias = '';
        if(!empty($this->options[self::EXPLAIN_ALIAS])){
            foreach($this->options[self::EXPLAIN_ALIAS] as $value){
                if(is_string($value)){
                    $alias = ' '.self::EXPRES_AS.' '.$value;
                }
            }
        }
        return $alias;
    }

    /**
     * @return string
     */
    protected function where()
    {
        $where = '';
        if(!empty($this->options[self::EXPLAIN_WHERE])){
            $where = ' '.self::EXPRES_WHERE.' 1 ';
            foreach($this->options[self::EXPLAIN_WHERE] as $value){
                if(is_string($value)){
                    $where .= ' '.self::EXPRES_AND.' '.$value;
                }elseif(is_array($value)){
                    foreach($value as $k=>$v){
                        $where .= ' '.self::EXPRES_AND.' '.$k.' = ? ';
                        $this->setParameter($v);
                    }
                }
            }
        }
        return $where;
    }


    /**
     * @return string
     */
    protected function orderby()
    {
        $orderby='';
        if(!empty($this->options[self::EXPLAIN_ORDER_BY])){
            $orderby = ' '.self::EXPRES_ORDER_BY.' ';
            foreach($this->options[self::EXPLAIN_ORDER_BY] as $value){
                if(is_string($value)){
                    $orderby .= $value.',';
                }
            }
            $orderby = rtrim($orderby,',');
        }
        return $orderby;
    }

    /**
     * @return string
     */
    protected function groupby()
    {
        $groupby = '';
        if(!empty($this->options[self::EXPLAIN_GROUP_BY])){
            $groupby = ' '.self::EXPRES_GROUP_BY.' ';
            foreach($this->options[self::EXPLAIN_GROUP_BY] as $value){
                if(is_string($value)){
                    $groupby .= $value.',';
                }
            }
            $groupby = rtrim($groupby,',');
        }
        return $groupby;

    }


    /**
     * @return string
     */
    protected function join()
    {
        $join = '';
        if(!empty($this->options[self::EXPLAIN_JOIN])){
            foreach($this->options[self::EXPLAIN_JOIN] as $value){
                if(!empty($value) && is_array($value)){
                    if(isset($value[self::TAG_JOIN_TYPE])){
                        $type = $value[self::TAG_JOIN_TYPE];
                        unset($value[self::TAG_JOIN_TYPE]);
                    }else{
                        $type = self::EXPRES_JOIN_INNER;
                    }
                    foreach($value as $k=>$v){
                        $join .= ' '.$type.' '.self::EXPRES_JOIN.' '.$this->config[SPHP_DB_PREFIX].$k .' '.self::EXPRES_ON.' '.$v;
                    }
                }
            }
        }
        return $join;
    }

    /**
     * @return string
     */
    protected function limit()
    {
        $limit = '';
        if(!empty($this->options[self::EXPLAIN_LIMIT])){
            foreach($this->options[self::EXPLAIN_LIMIT] as $value){
                if(is_string($value) || is_int($value)){
                    $limit = ' '.self::EXPRES_LIMIT.' '.$value;
                }
            }
        }
        return $limit;
    }


    public function select()
    {
        $this->reSet();
        $this->expres =
            self::EXPRES_SELECT.' '.$this->field().
            ' '.self::EXPRES_FROM.' '.$this->config[SPHP_DB_PREFIX].$this->table->getTableName().$this->alias().$this->join().$this->where().$this->groupby().$this->orderby().$this->limit();
        $this->clearOptions();
    }


    public function insert($data)
    {
        $this->reSet();
        $fieldStr = $paramStr = '(';
        foreach($data as $k => $v){
            $fieldStr .= '`'.$k.'`,';
            $paramStr .= '?,';
            $this->setParameter($v);
        }
        $fieldStr = rtrim($fieldStr,',').')';
        $paramStr = rtrim($paramStr,',').')';
        $this->expres =
            self::EXPRES_INSERT.' `'.$this->config[SPHP_DB_PREFIX].$this->table->getTableName().'` '.$fieldStr.' VALUES '.$paramStr;
        $this->clearOptions();
    }

    public function update($data)
    {
        $this->reSet();
        $this->expres =
            self::EXPRES_UPDATE.' `'.$this->config[SPHP_DB_PREFIX].$this->table->getTableName().'` SET';
        foreach($data as $k => $v){
            $this->expres .= ' `'.$k.'`=?,';
            $this->setParameter($v);
        }
        $this->expres = rtrim($this->expres,',').$this->where().$this->limit();
        $this->clearOptions();
    }

    public function delete()
    {
        $this->reSet();
        $this->expres =
            self::EXPRES_DELETE.' '.self::EXPRES_FROM.' '.$this->config[SPHP_DB_PREFIX].$this->table->getTableName().$this->where().$this->limit();
        $this->clearOptions();
    }

}



