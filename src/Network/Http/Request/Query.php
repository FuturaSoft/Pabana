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
 * @since         1.0
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Network\Http\Request;

use Carbon\Carbon;

/**
 * Query class
 *
 * Parse $_GET
 */
class Query
{
    /**
     * @var     Array   List of query variable
     * @since   1.2
     */
    private $variableList = [];

    /**
     * Constructor
     *
     * @since   1.2
     */
    public function __construct()
    {
        // Get variable list
        $this->variableList = $this->prepareVariable($_GET);
    }

    /**
     * Recursively prepare variable
     *
     * @since   1.2
     *
     * @param   mixed  $variableMixed   Array or string variable
     *
     * @return  mixed   Return input
     */
    private function prepareVariable($variableMixed)
    {
        if (is_array($variableMixed)) {
            foreach ($variableMixed as $key => $variable) {
                $variableMixed[$key] = $this->prepareVariable($variable);
            }
            return $variableMixed;
        } else {
            $variableMixed = trim($variableMixed);
            return urldecode($variableMixed);
        }
    }

    /**
     * Get $_GET parameter
     *
     * @since   1.2
     *
     * @param   string  $key    Key of input
     *
     * @return  mixed   Return input
     */
    public function __get(string $key) {
        return $this->get($key);
    }

    /**
     * Return all input
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     *
     * @return  array
     */
    public function all()
    {
        return $this->get();
    }

    /**
     * Return boolean value of a variable
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     * @param   boolean $falseIfNotIsset    (Optional) Return false if key doesn't exist
     *
     * @return  boolean
     */
    public function boolean($key, $falseIfNotIsset = false)
    {
        if (
            $falseIfNotIsset === true
            && !$this->has($key)
        ) {
            return false;
        }
        $aTestBoolean = [1, "1", true, "true", "on", "yes"];
        if (in_array($this->get($key, 0), $aTestBoolean, true)) {
            return true;
        }
        return false;
    }

    /**
     * Return Carbon date
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     * @param   string  $format (Optional) Format of Carbon object (default: Y-m-d)
     *
     * @return  \Carbon
     */
    public function date($key, $format = 'Y-m-d')
    {
        return Carbon::createFromFormat($format, $this->get($key));
    }

    /**
     * Check if variables is filled
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     *
     * @return  boolean
     */
    public function filled($key)
    {
        if (
            $this->has($key)
            && !empty($this->variableList[$key])
        ) {
            return true;
        }
        return false;
    }

    /**
     * Get $_GET parameter
     *
     * @since   1.2
     *
     * @param   string  $key           (Optional) Key of query
     * @param   string  $defaultValue  (Optional) Default value
     *
     * @return  mixed   Return query
     */
    public function get($key = '', $defaultValue = null)
    {
        if (empty($key)) {
            return $this->variableList;
        }
        if (isset($this->variableList[$key])) {
            return $this->variableList[$key];
        }
        if ($defaultValue !== null) {
            return $defaultValue;
        }
        throw new \Exception('Query "' . $key . "' doesn\'t exist.");
        return null;
    }

    /**
     * Check if variables exist
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     *
     * @return  boolean
     */
    public function has($key)
    {
        if (isset($this->variableList[$key])) {
            return true;
        }
        return false;
    }

    /**
     * Merge array in query array
     *
     * @since   1.2
     *
     * @param   array   $data   Array merge
     *
     * @return  this
     */
    public function merge($data)
    {
        $this->variableList = array_merge($this->variableList, $data);
        return $this;
    }

    /**
     * Return old value
     *
     * @since   1.2
     *
     * @param   string  $key           Key of input
     * @param   string  $defaultValue  (Optional) Default value
     *
     * @return  mixed
     */
    public function old($key, $defaultValue = null)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        if ($defaultValue !== null) {
            return $defaultValue;
        }
        throw new \Exception('GET variable "' . $key . '" doesn\'t exist.');
        return null;
    }

    /**
     * Replace variable content in query
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     * @param   mixed   $value  New value of variable
     *
     * @return  this
     */
    public function replace($key, $value)
    {
        $this->variableList[$key] = $value;
        return $this;
    }
}
