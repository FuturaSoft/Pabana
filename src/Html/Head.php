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

use Pabana\Html\Head\Charset;
use Pabana\Html\Head\Css;
use Pabana\Html\Head\Icon;
use Pabana\Html\Head\Link;
use Pabana\Html\Head\Meta;
use Pabana\Html\Head\Title;

class Head
{
    public $Charset;
    public $Css;
    public $Icon;
    public $Link;
    public $Meta;
    public $Title;

    public function __construct()
    {
        $this->Charset = new Charset();
        $this->Css = new Css();
        $this->Icon = new Icon();
        $this->Link = new Link();
        $this->Meta = new Meta();
        $this->Title = new Title();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function clean()
    {
        $this->Charset->clean();
        $this->Css->clean();
        $this->Icon->clean();
        $this->Link->clean();
        $this->Meta->clean();
        $this->Title->clean();
    }

    public function render()
    {
        $sHtml = '';
        $sHtml .= $this->Charset->render();
        $sHtml .= $this->Meta->render();
        $sHtml .= $this->Title->render();
        $sHtml .= $this->Link->render();
        $sHtml .= $this->Css->render();
        $sHtml .= $this->Icon->render();
        return $sHtml;
    }
}
