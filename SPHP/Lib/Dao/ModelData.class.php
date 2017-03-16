<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/14
 */

namespace SPHPCore\Lib\Dao;


class ModelData extends ModelObject
{

    private static $_tableModel;
    private $_data;
    private $_update;
    private $_statement;

    protected $relation;


    /**
     * @return mixed
     */
    protected static function getTableModel()
    {
        if(empty(self::$_tableModel))
            self::$_tableModel = new TableModel(get_called_class());
        return self::$_tableModel;
    }

    /**
     * @param mixed $tableModel
     */
    public function setTableModel($tableModel)
    {
        $this->_tableModel = $tableModel;
    }

    /**
     * @return mixed
     */
    public function getRelation()
    {
        return self::$relation;
    }

    /**
     * @param mixed $relation
     */
    public function setRelation($relation)
    {
        $this->relation[] = $relation;
        parent::offsetSet($relation->getName(), $relation);
    }



    /**
     * @param $index
     * @return string
     */
    private function __propertyIndex($index){
        $lcIndex = lcfirst($index);
        if(isset($this->$index)){
            return $index;
        }elseif(isset($this->$lcIndex)){
            return $lcIndex;
        }else{
            $index = '';
            for($i=0;$i<strlen($lcIndex);$i++){
                $ascii = ord($lcIndex{$i});
                if($ascii >= 65 && $ascii <= 90){
                    $index .= '_';
                    $lcIndex{$i} = strtolower($lcIndex{$i});
                }
                $index .= $lcIndex{$i};
            }
            return $index;
        }
    }


    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return mixed
     */
    public function getUpdate()
    {
        return $this->_update;
    }

    /**
     * @param $index
     * @param $value
     */
    public function setUpdate($index,$value)
    {
        $this->_update[$index] = $value;
    }



    /**
     * @return mixed
     */
    public function getStatement()
    {
        return $this->_statement;
    }

    /**
     * @param mixed $statement
     */
    public function setStatement($statement)
    {
        $this->_statement = $statement;
    }

    /**
     * ModelData constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->setData($data);
    }


    /**
     * @return bool
     */
    public function isNew()
    {
        return empty($this->_statement);
    }


    /**
     * @param $name
     * @param $arguments
     * @return null
     */
    public function __call($name, $arguments)
    {
        if(strpos($name,'get') === 0){
            $index  = $this->__propertyIndex(substr($name,3));
            return isset($this->$index) ? $this->$index : null;
        }elseif(
            strpos($name,'set') === 0 &&
            isset($arguments[0]) &&
            (is_string($arguments[0]) || is_int($arguments[0]))
        ){
            $index  = $this->__propertyIndex(substr($name,3));
            $this->$index = $arguments[0];
        }else{

        }
    }

    /**
     * @param mixed $index
     * @param mixed $newval
     */
    public function offsetSet($index, $newval)
    {
        $data = $this->getData();
        if(!isset($data[$index]) || $data[$index] != $newval){
            parent::offsetSet($index, $newval);
            $this->setUpdate($index,$newval);
        }
        unset($data);
    }

    /**
     * @return int|bool
     */
    public function save()
    {
        $result = 0;
        $isNew = $this->isNew();
        $update = $this->getUpdate();
        if(!empty($update)){
            $tableModel = $this->getTableModel();
            $tableModel->setPrototype($this);
            if($isNew){
                $result = $tableModel->insert($update);
            }else{
                $data = $this->getData();
                $primaryKey = $this::__primaryKey();
                if(!empty($primaryKey) && !empty($data[$primaryKey])){
                    $primary = $data[$primaryKey];
                    $result = $tableModel->update($update,$primary);
                    if(!Relation::notifyRelation($this->relation,__FUNCTION__)){
                        $result = false;
                    }
                    unset($data,$primaryKey,$primary);
                }
            }
        }
        unset($update);
        return $result;
    }

    public function delete(){}




}