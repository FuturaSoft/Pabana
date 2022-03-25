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
 * @since         1.2
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Mvc;

/**
 * Guard class
 *
 * Manage Guard
 */
class Guard
{
    /**
     * @var     integer   Define HTTP error code
     * @since   1.2
     */
    private $errorCode;

    /**
     * @var     array   Define error return
     * @since   1.2
     */
    private $errorData;

    /**
     * Initialize guard
     *
     * @since   1.2
     * @return  void
     */
    public function __construct()
    {
        // Define default error code
        $this->errorCode = 500;
        $this->errorData = [
            'code' => 'UNKNOW_ERROR',
            'message' => 'Unknow error'
        ];
    }

    /**
     * Get error code
     *
     * @since   1.2
     * @return  void
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Get error data
     *
     * @since   1.2
     * @return  void
     */
    public function getErrorData()
    {
        return $this->errorData;
    }

    /**
     * Define Guard error
     *
     * @since   1.2
     * @return  void
     */
    public function setError($aErrorData = null, $iErrorCode = 500)
    {
        $this->errorData = $aErrorData;
        $this->errorCode = $iErrorCode;
    }
}
