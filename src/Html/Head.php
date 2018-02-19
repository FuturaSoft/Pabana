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

/**
 * Head class
 *
 * Manipulate Html\Head object
 */
class Head
{
    /**
     * @var     \Pabana\Html\Head\Charset Charset object
     * @since   1.0.0
     */
    public $Charset;

    /**
     * @var     \Pabana\Html\Head\Css Css object
     * @since   1.0.0
     */
    public $Css;

    /**
     * @var     \Pabana\Html\Head\Icon Icon object
     * @since   1.0.0
     */
    public $Icon;

    /**
     * @var     \Pabana\Html\Head\Link Link object
     * @since   1.0.0
     */
    public $Link;

    /**
     * @var     \Pabana\Html\Head\Meta Meta object
     * @since   1.0.0
     */
    public $Meta;

    /**
     * @var     \Pabana\Html\Head\Title Title object
     * @since   1.0.0
     */
    public $Title;

    /**
     * Constructor
     *
     * Initialize Charset, Css, Icon, Link, Meta and Title Object
     *
     * @since   1.0.0
     */
    public function __construct()
    {
        $this->Charset = new Charset();
        $this->Css = new Css();
        $this->Icon = new Icon();
        $this->Link = new Link();
        $this->Meta = new Meta();
        $this->Title = new Title();
    }

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code to initialize all head's object
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Clean previous configuration
     *
     * Clean previous configuration in Charset, Css, Icon, Link, Meta and Title Object
     *
     * @since   1.0.0
     * @return void
     */
    public function clean()
    {
        $this->Charset->clean();
        $this->Css->clean();
        $this->Icon->clean();
        $this->Link->clean();
        $this->Meta->clean();
        $this->Title->clean();
    }

    /**
     * Render
     *
     * Return HTML code for initialize all head's object
     *
     * @since   1.0.0
     * @return  string Html code to initialize all head's object
     */
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