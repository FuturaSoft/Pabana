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
 * File class
 *
 * Parse $_FILES
 */
class File
{
    /**
     * @var     Array   List of input variable
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
        $this->variableList = $_FILES;
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
    public function __get(string $key) {
        return $this->get($key);
    }

    /**
     * Get $_FILES parameter
     *
     * @since   1.2
     *
     * @param   string  $key    Key of input
     *
     * @return  mixed   Return input
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new \Exception('File "' . $key . "' doesn\'t exist.");
            return null;
        }
        if (!$this->isValid($key)) {
            throw new \Exception('File upload "' . $key . "' failed.");
            return null;
        }
        return new UploadedFile($this->variableList[$key]['tmp_name']);
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
     * Check if file is valid
     *
     * @since   1.2
     *
     * @param   string  $key    Key of file
     *
     * @return  mixed   Return input
     */
    public function isValid($key)
    {
        if (!$this->has($key)) {
            return false;
        }
        if ($this->variableList[$key]['error'] != UPLOAD_ERR_OK) {
            return false;
        }
        return true;
    }

    /**
     * Return old value
     *
     * @since   1.2
     *
     * @param   string  $key    Key of file
     *
     * @return  mixed
     */
    public function old($key)
    {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }
        throw new \Exception('FILES variable "' . $key . '" doesn\'t exist.');
        return null;
    }
}
