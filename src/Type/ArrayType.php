<?php
/**
 * Pabana : PHP Framework (https://pabana.futurasoft.fr)
 * Copyright (c) FuturaSoft (https://futurasoft.fr)
 *
 * Licensed under BSD-3-Clause License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) FuturaSoft (https://futurasoft.fr)
 * @link          https://pabana.futurasoft.fr Pabana Project
 * @since         1.1
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Type;

/**
 * Manipulate array
 */
class ArrayType
{
    /**
     * @const During a merge, priority is for argument
     * @since   1.1
     */
    const MERGE_ARGUMENT = 1;

    /**
     * @const During a merge, priority is for object
     * @since   1.1
     */
    const MERGE_OBJECT = 2;

    /**
     * @const During a merge, all value is keeped and indexes is delete
     * @since   1.1
     */
    const MERGE_KEEP_VALUE = 3;

    /**
     * @const Sort ascendingly
     * @since   1.1
     */
    const SORT_ASC = SORT_ASC;

    /**
     * @const Sort descendingly
     * @since   1.1
     */
    const SORT_DESC = SORT_DESC;

    /**
     * @const Compare items normally (don't change types)
     * @since   1.1
     */
    const SORT_REGULAR = SORT_REGULAR;

    /**
     * @const Compare items numerically
     * @since   1.1
     */
    const SORT_NUMERIC = SORT_NUMERIC;

    /**
     * @const Compare items as strings
     * @since   1.1
     */
    const SORT_STRING = SORT_STRING;

    /**
     * @const Compare items as strings, based on the current locale.
     * @since   1.1
     */
    const SORT_LOCALE_STRING = SORT_LOCALE_STRING;

    /**
     * @const Compare items as strings using "natural ordering"
     * @since   1.1
     */
    const SORT_NATURAL = SORT_NATURAL;

    /**
     * @var     array Array manipulate by this class.
     * @since   1.1
     */
    private $arrayVar;
    
    /**
     * Constructor
     *
     * Initialize class with a String
     *
     * @since   1.1
     * @return  void
     */
    public function __construct($arrayVar = array())
    {
        $this->arrayVar = $arrayVar;
    }

    /**
     * Append a value to array
     *
     * @since   1.1
     * @param   mixed $value Value of item
     * @return  void
     */
    public function append($value)
    {
        $this->arrayVar[] = $value;
    }

    /**
     * Clean all item in array
     *
     * @since   1.1
     * @return  void
     */
    public function clean()
    {
        $this->arrayVar = array();
    }

    /**
     * Return value of array's item by key or index
     *
     * @since   1.1
     * @param   int|string $indexOrName Index or name of key
     * @return  mixed|bool Value of array's item or false if not exist
     */
    public function get($indexOrName)
    {
        if (isset($this->arrayVar[$indexOrName])) {
            return $this->arrayVar[$indexOrName];
        }
        return false;
    }

    /**
     * Return one or more random entries out of an array
     *
     * @since   1.1
     * @param   int $itemNumber Number of item return by method
     * @return  mixed if $itemNumber = 1 return only one key, else return an array of key.
     */
    public function getRand($itemNumber = 1)
    {
        return array_rand($this->arrayVar, $itemNumber);
    }

    /**
     * Insert a value at defined index
     *
     * @since   1.1
     * @param   int $index Index of key
     * @param   mixed $value Value of item
     * @return  bool True if success or false if not exist
     */
    public function insert($index, $value)
    {
        array_splice($this->arrayVar, $index, 0, $value);
    }

    /**
     * Check if current array is associative
     *
     * @since   1.1
     * @return  bool True if associative or false if not
     */
    public function isAssociative()
    {
        return count(array_filter(array_keys($this->arrayVar), 'is_string')) > 0;
    }

    /**
     * Check if current array is sequential
     *
     * @since   1.1
     * @return  bool True if sequential or false if not
     */
    public function isSequential()
    {
        return array_keys($this->arrayVar) !== range(0, count($this->arrayVar) - 1);
    }

    /**
     * Return value of array's item by key or index
     *
     * @since   1.1
     * @param   array|\Pabana\Type\ArrayType $arrayOrObjectVar Array or Object
     * @param   int $priority Merge priority
     * @return  void
     */
    public function merge($arrayOrObjectVar, $priority = self::MERGE_ARGUMENT)
    {
        if (is_object($arrayOrObjectVar)) {
            $arrayVar = $arrayOrObjectVar->toArray();
        } else {
            $arrayVar = $arrayOrObjectVar;
        }
        if ($priority == 1) {
            $this->arrayVar = array_merge($this->arrayVar, $arrayOrObjectVar);
        } else if ($priority == 2) {
            $this->arrayVar = array_merge($arrayOrObjectVar, $this->arrayVar);
        } else if ($priority == 3) {
            $arrayMerge = array();
            $arrayIndex = 0;
            foreach ($this->arrayVar as $value) {
                $arrayMerge[$arrayIndex] = $value;
                $arrayIndex++;
            }
            foreach ($arrayVar as $value) {
                $arrayMerge[$arrayIndex] = $value;
                $arrayIndex++;
            }
            $this->arrayVar = $arrayMerge;
        }
    }

    /**
     * Prepend a value to array
     *
     * @since   1.1
     * @param   mixed $value Value of item
     * @return  void
     */
    public function prepend($value)
    {
        array_unshift($this->arrayVar, $value);
    }

    /**
     * Remove an item
     *
     * @since   1.1
     * @param   int|string $indexOrName Index or name of key
     * @return  bool True if success or false if not exist
     */
    public function remove($indexOrName)
    {
        if (isset($this->arrayVar[$indexOrName])) {
            unset($this->arrayVar[$indexOrName]);
            return true;
        }
        return false;
    }

    /**
     * Define an item in Array
     *
     * @since   1.1
     * @param   int|string $indexOrName Index or name of key
     * @param   mixed $value Value of item
     * @param   bool $force Force change of value if key already exist
     * @return  bool True if success or false if not exist
     */
    public function set($indexOrName, $value, $force = true)
    {
        if (isset($this->arrayVar[$indexOrName]) && $force === false) {
            return false;
        }
        $this->arrayVar[$indexOrName] = $value;
        return true;
    }

    /**
     * Sort an array by value
     *
     * @since   1.1
     * @param   int  $order Sort order (SORT_ASC or SORT_DESC)
     * @param   int  $type Sort type (SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING or SORT_NATURAL)
     * @param   bool $caseSensitive Define if sort is case sensitive
     * @return  $this Return current object
     */
    public function sort($order = self::SORT_ASC, $type = self::SORT_REGULAR, $caseSensitive = true)
    {
        if ($caseSensitive === false) {
            $type = $type + SORT_FLAG_CASE;
        }
        if ($this->isAssociative() === true) {
            if ($order == self::SORT_ASC) {
                asort($this->arrayVar, $type);
            } else {
                var_dump($type);
                arsort($this->arrayVar, $type);
            }
        } else {
            if ($order == self::SORT_ASC) {
                sort($this->arrayVar, $type);
            } else {
                rsort($this->arrayVar, $type);
            }
        }
        return $this;
    }

    /**
     * Sort an array by key
     *
     * @since   1.1
     * @param   int  $order Sort order (SORT_ASC or SORT_DESC)
     * @param   int  $type Sort type (SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING or SORT_NATURAL)
     * @param   bool $caseSensitive Define if sort is case sensitive
     * @return  $this Return current object
     */
    public function sortByKey($order = self::SORT_ASC, $type = self::SORT_REGULAR, $caseSensitive = true)
    {
        if ($caseSensitive === false) {
            $type = $type + SORT_FLAG_CASE;
        }
        if ($this->isAssociative() === true) {
            array_multisort(array_keys($this->arrayVar), $order, $type, $this->arrayVar);
        } else {
            if ($order == self::SORT_ASC) {
                ksort($this->arrayVar, $type);
            } else {
                krsort($this->arrayVar, $type);
            }
        }
        return $this;
    }

    /**
     * Shuffle content of array
     *
     * @since   1.1
     * @return  $this Return current object
     */
    public function shuffle()
    {
        shuffle($this->arrayVar);
        return $this;
    }

    /**
     * Return Array.
     *
     * @since   1.1
     * @return  array Array.
     */
    public function toArray()
    {
        return $this->arrayVar;
    }
}
