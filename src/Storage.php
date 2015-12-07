<?php

/**
 * Created by PhpStorm.
 * User: a6y
 * Date: 07.12.15
 * Time: 12:37
 */

/**
 * Class Storage
 * Shared memory key => value storage
 */

class Storage implements ArrayAccess
{
    private $__mem = NULL;
    private $__data = array();


    public function __construct($size) {
        $this->__mem = new SharedMemory($size);
        $this->__data = $this->__mem->get();
    }

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->__data);
    }

    public function offsetGet($offset) {
        return $this->__data[$offset];
    }

    public function offsetSet($offset, $value) {
        $this->__data[$offset] = $value;
        // Save in memory
        $this->__mem->set($this->__data);
    }

    public function offsetUnset($offset) {
        unset($this->__data[$offset]);
        // Save in memory
        $this->__mem->set($this->__data);
    }

    public function destroy() {
        $this->__mem->destroy();
    }

    public function search ($val) {
        return array_search($val, $this->__data);
    }
}