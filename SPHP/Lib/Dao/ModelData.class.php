<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/14
 */

namespace SPHPCore\Lib\Dao;


class ModelData extends ModelObject
{
    private
        $_data,
        $_statement;

    /**
     * @return mixed
     */
    private function getTableModel()
    {
        $tableModel = new TableModel();
        $tableModel->setTableName($this->__tableName());
        $tableModel->setPrimaryKey($this->__primaryKey());
        $tableModel->setPrototype($this);
        return $tableModel;
    }

    /**
     * @param $index
     * @return string
     */
    private function propertyIndex($index){
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
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return bool
     */
    public function isNew()
    {
        return empty($this->_statement);
    }


    /**
     * @return mixed
     */
    public static function tb()
    {
        $model = get_called_class();
        $prototype = new $model();
        return $prototype->getTableModel();
    }

    /**
     * @param $name
     * @param $arguments
     * @return null
     */
    public function __call($name, $arguments)
    {
        if(strpos($name,'get') === 0){
            $index  = $this->propertyIndex(substr($name,3));
            return isset($this->$index) ? $this->$index : null;
        }elseif(
            strpos($name,'set') === 0 &&
            isset($arguments[0]) &&
            (is_string($arguments[0]) || is_int($arguments[0]))
        ){
            $index  = $this->propertyIndex(substr($name,3));
            $this->$index = $arguments[0];
        }
    }

    /**
     * @return int|bool
     */
    public function save()
    {
        $result = 0;
        $update = array();
        $isNew = $this->isNew();
        if($isNew){
            $update = $this->toArray();
        }else{
            $data = $this->getData();
            $primaryKey = $this->__primaryKey();
            if(!empty($primaryKey) && !empty($data[$primaryKey])){
                $primary = $data[$primaryKey];
                foreach($this->toArray() as $field => $value){
                    if(!isset($data[$field]) || $data[$field] != $value){
                        $update[$field] = $value;
                    }
                }
            }
            unset($data,$primaryKey);
        }
        if(!empty($update)){
            $tableModel = $this->getTableModel();
            $result = $isNew ? $tableModel->insert($update) : $tableModel->update($update,$primary);
        }
        unset($update);
        return $result;
    }

}