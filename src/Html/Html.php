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
     * @since   1.1
     */
    public $doctype;

    /**
     * @var     \Pabana\Html\Head Head object
     * @since   1.1
     */
    public $head;

    /**
     * @var     \Pabana\Html\Script Script object
     * @since   1.1
     */
    public $script;

    /**
     * @var     \Pabana\Html\Doctype Doctype object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Doctype;

    /**
     * @var     \Pabana\Html\Head Head object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Head;

    /**
     * @var     \Pabana\Html\Script Script object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Script;

    /**
     * Constructor
     *
     * Initialize Doctype, Head and Script Object
     *
     * @since   1.0
     */
    public function __construct()
    {
        $this->doctype = new Doctype();
        $this->head = new Head();
        $this->script = new Script();
        // To maintain compatibility with version 1.0
        $this->Doctype = $this->doctype;
        $this->Head = $this->head;
        $this->Script = $this->script;
    }

    /**
     * Clean previous configuration
     *
     * Clean previous configuration in Doctype, Head and Script Object
     *
     * @since   1.0
     * @return  void
     */
    public function clean()
    {
        $this->doctype->clean();
        $this->head->clean();
        $this->script->clean();
    }
}
