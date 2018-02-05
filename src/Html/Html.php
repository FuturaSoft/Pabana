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

class Html
{
    public $Doctype;
    public $Head;
    public $Script;

    public function __construct()
    {
        $this->Doctype = new Doctype();
        $this->Head = new Head();
        $this->Script = new Script();
    }

    public function clean()
    {
        $this->Doctype->clean();
        $this->Head->clean();
        $this->Script->clean();
    }
}
