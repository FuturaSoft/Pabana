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
     * @since   1.1
     */
    public $charset;

    /**
     * @var     \Pabana\Html\Head\Css Css object
     * @since   1.1
     */
    public $css;

    /**
     * @var     \Pabana\Html\Head\Icon Icon object
     * @since   1.1
     */
    public $icon;

    /**
     * @var     \Pabana\Html\Head\Link Link object
     * @since   1.1
     */
    public $link;

    /**
     * @var     \Pabana\Html\Head\Meta Meta object
     * @since   1.1
     */
    public $meta;

    /**
     * @var     \Pabana\Html\Head\Title Title object
     * @since   1.1
     */
    public $title;

    /**
     * @var     \Pabana\Html\Head\Charset Charset object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Charset;

    /**
     * @var     \Pabana\Html\Head\Css Css object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Css;

    /**
     * @var     \Pabana\Html\Head\Icon Icon object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Icon;

    /**
     * @var     \Pabana\Html\Head\Link Link object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Link;

    /**
     * @var     \Pabana\Html\Head\Meta Meta object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Meta;

    /**
     * @var     \Pabana\Html\Head\Title Title object
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Title;

    /**
     * Constructor
     *
     * Initialize Charset, Css, Icon, Link, Meta and Title Object
     *
     * @since   1.0
     */
    public function __construct()
    {
        $this->charset = new Charset();
        $this->css = new Css();
        $this->icon = new Icon();
        $this->link = new Link();
        $this->meta = new Meta();
        $this->title = new Title();
        // To maintain compatibility with version 1.0
        $this->Charset = $this->charset;
        $this->Css = $this->css;
        $this->Icon = $this->icon;
        $this->Link = $this->link;
        $this->Meta = $this->meta;
        $this->Title = $this->title;
    }

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0
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
     * @since   1.0
     * @return void
     */
    public function clean()
    {
        $this->charset->clean();
        $this->css->clean();
        $this->icon->clean();
        $this->link->clean();
        $this->meta->clean();
        $this->title->clean();
    }

    /**
     * Render
     *
     * Return HTML code for initialize all head's object
     *
     * @since   1.0
     * @return  string Html code to initialize all head's object
     */
    public function render()
    {
        $htmlContent = '';
        $htmlContent .= $this->charset->render();
        $htmlContent .= $this->meta->render();
        $htmlContent .= $this->title->render();
        $htmlContent .= $this->link->render();
        $htmlContent .= $this->css->render();
        $htmlContent .= $this->icon->render();
        return $htmlContent;
    }
}
