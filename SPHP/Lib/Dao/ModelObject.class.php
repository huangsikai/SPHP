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
     * @param array $data
     */
    public function __construct($data = array()) {
        parent::__construct($data,\ArrayObject::ARRAY_AS_PROPS);
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

    /**
     * 给每个元素执行个回调
     *
     * @param  callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        $array = $this->getArrayCopy();
        foreach ($array as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }

}