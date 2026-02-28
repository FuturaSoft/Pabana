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
 * Input class
 *
 * Parse $_POST
 */
class Input
{
    /**
     * @var     array   List of input variable
     * @since   1.2
     */
    private array $variableList = [];

    /**
     * Constructor
     *
     * @since   1.2
     */
    public function __construct()
    {
        // Get variable list
        $this->variableList = $this->prepareVariable($_POST);
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
    private function prepareVariable(mixed $variableMixed): mixed
    {
        if (is_array($variableMixed)) {
            foreach ($variableMixed as $key => $variable) {
                $variableMixed[$key] = $this->prepareVariable($variable);
            }
            return $variableMixed;
        } else {
            return trim($variableMixed);
        }
    }

    /**
     * Get $_POST parameter
     *
     * @since   1.2
     *
     * @param   string  $key    Key of input
     *
     * @return  mixed   Return input
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Return all input
     *
     * @since   1.2
     *
     * @return  array
     */
    public function all(): array
    {
        return $this->get();
    }

    /**
     * Return boolean value of a variable
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     * @param   bool $falseIfNotIsset    (Optional) Return false if key doesn't exist
     *
     * @return  bool
     */
    public function boolean(string $key, bool $falseIfNotIsset = false): bool
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
     * @return  \Carbon\Carbon
     */
    public function date(string $key, string $format = 'Y-m-d'): Carbon
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
     * @return  bool
     */
    public function filled(string $key): bool
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
     * Get $_POST parameter
     *
     * @since   1.2
     *
     * @param   string  $key           (Optional) Key of input
     * @param   mixed   $defaultValue  (Optional) Default value
     *
     * @return  mixed   Return input
     */
    public function get(string $key = '', mixed $defaultValue = null): mixed
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
        throw new \Exception('Input "' . $key . "' doesn\'t exist.");
    }

    /**
     * Check if variables exist
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     *
     * @return  bool
     */
    public function has(string $key): bool
    {
        if (isset($this->variableList[$key])) {
            return true;
        }
        return false;
    }

    /**
     * Merge array in input array
     *
     * @since   1.2
     *
     * @param   array   $data   Array merge
     *
     * @return  static
     */
    public function merge(array $data): static
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
     * @param   mixed   $defaultValue  (Optional) Default value
     *
     * @return  mixed
     */
    public function old(string $key, mixed $defaultValue = null): mixed
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        if ($defaultValue !== null) {
            return $defaultValue;
        }
        throw new \Exception('POST variable "' . $key . '" doesn\'t exist.');
    }

    /**
     * Replace variable content in input
     *
     * @since   1.2
     *
     * @param   string  $key    Key of variable
     * @param   mixed   $value  New value of variable
     *
     * @return  static
     */
    public function replace(string $key, mixed $value): static
    {
        $this->variableList[$key] = $value;
        return $this;
    }
}
