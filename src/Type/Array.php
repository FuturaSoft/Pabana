<?php
namespace Pabana\Type;

use Pabana\Debug\Error;

class Array
{
    private $armArray;

    public function __construct($armArray = array())
    {
        $this->armArray = $armArray;
    }

    public function add($mValue, $mKey = '')
    {
        $this->armArray[$mKey] = $mValue;
    }

    public function addAfter($mIndex, $mValue, $mKey = '')
    {
        if(is_numeric($mIndex)) {
            $nIndex = $mIndex;
        } else {
            $nIndex = $this->getIndex($mIndex);
        }
        $this->addAt($nIndex, $mValue, $mKey);
    }

    public function addAt($nIndex, $mValue, $mKey = '')
    {
        $armTmpArray = array_slice($this->armArray, 0, $nIndex, true);
        $armTmpArray[$mKey] = $mValue;
        $armTmpArray += array_slice($this->armArray, $nIndex, $this->size() - $nIndex, true);
        $this->setAll($armTmpArray);
        unset($armTmpArray);
    }

    public function addBefore($nIndex, $mValue, $mKey = '')
    {
        if(is_numeric($mIndex)) {
            $nIndex = $mIndex;
        } else {
            $nIndex = $this->getIndex($mIndex);
        }
        $nIndex--;
        $this->addAt($nIndex, $mValue, $mKey);
    }

    public function append($mValue)
    {
        $this->armArray[] = $mValue;
    }

    public function clean()
    {
        $this->armArray = array();
    }

    public function exists($mKey)
    {
        return isset($this->armArray[$mKey]);
    }

    public function get($mKey)
    {
        return $this->armArray[$mKey];
    }

    public function getAll()
    {
        return $this->armArray;
    }

    public function getIndex($mKey)
    {
        return array_search($mKey, array_keys($this->armArray));
    }

    public function prepend($mValue)
    {
        array_unshift($this->armArray, $mValue);
    }

    public function set($mKey, $mValue)
    {
        $this->armArray[$mKey] = $mValue;
    }

    public function setAll($armArray)
    {
        $this->armArray = $armArray;
    }

    public function size()
    {
        return count($this->armArray);
    }

    public function remove($mKey)
    {
        return unset($this->armArray[$mKey]);
    }
}