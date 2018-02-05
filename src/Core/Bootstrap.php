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
 * @since         1.0.0
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Core;

use \Pabana\Network\Http\Request;

/**
 * Bootstrap class
 *
 * Helper method for Bootstrap file
 */
class Bootstrap
{
    /**
     * @var \Pabana\Network\Http\Request Request helper to access to Request class
     */
    public $Request;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Create an object Request for usage in Bootstrap page
        $this->Request = new Request();
    }
}
