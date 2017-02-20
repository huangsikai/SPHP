<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/13
 */

namespace SPHPCore\Lib\Dao;



class ModelObject extends \ArrayObject
{

    /**
     * ModelObject constructor.
     */
    public function __construct() {
        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * @return array
     */
    public function toArray(){
        $array = $this->getArrayCopy();
        foreach ($array as &$value)
            ($value instanceof self) && $value = $value->toArray();
        return $array;
    }

    /**
     * @return bool
     */
    public function isEmpty(){
        return $this->count() <= 0;
    }
}