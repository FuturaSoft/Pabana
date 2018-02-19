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
namespace Pabana\Html;

use Pabana\Html\Doctype;
use Pabana\Html\Head;

/**
 * Html class
 *
 * Manipulate Html object
 */
class Html
{
    /**
     * @var     \Pabana\Html\Doctype Doctype object
     * @since   1.0.0
     */
    public $Doctype;

    /**
     * @var     \Pabana\Html\Head Head object
     * @since   1.0.0
     */
    public $Head;

    /**
     * @var     \Pabana\Html\Script Script object
     * @since   1.0.0
     */
    public $Script;

    /**
     * Constructor
     *
     * Initialize Doctype, Head and Script Object
     *
     * @since   1.0.0
     */
    public function __construct()
    {
        $this->Doctype = new Doctype();
        $this->Head = new Head();
        $this->Script = new Script();
    }

    /**
     * Clean previous configuration
     *
     * Clean previous configuration in Doctype, Head and Script Object
     *
     * @since   1.0.0
     * @return void
     */
    public function clean()
    {
        $this->Doctype->clean();
        $this->Head->clean();
        $this->Script->clean();
    }
}