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
namespace Pabana\Mvc;

use Pabana\Core\Configuration;
use Pabana\Html\Html;
use Pabana\Mvc\Layout;
use Pabana\Mvc\Model;
use Pabana\Mvc\View;
use Pabana\Network\Http\Request;
use Pabana\Routing\Router;

/**
 * Mvc class
 *
 * Manage Mvc
 */
class Mvc
{
    /**
     * @var     \Pabana\Html\Html Object Html to manipulate Html.
     * @since   1.0.0
     */
    protected $Html;

    /**
     * @var     \Pabana\Mvc\Layout Object Layout to manipulate Layout.
     * @since   1.0.0
     */
    protected $Layout;

    /**
     * @var     \Pabana\Mvc\Model Object Model to manipulate Model.
     * @since   1.0.0
     */
    protected $Model;

    /**
     * @var     \Pabana\Network\Http\Request Object Request to get information about http request.
     * @since   1.0.0
     */
    protected $Request;

    /**
     * @var     \Pabana\Mvc\View Object View to manipulate View.
     * @since   1.0.0
     */
    protected $View;

    /**
     * Initialize controller
     *
     * Initialize helper object Html, Layout, Model, Request and View
     *
     * @since   1.0.0
     * @return  void
     */
    final public function __construct()
    {
        // Load Html\Html helper to $Html var
        $this->Html = new Html();
        // Load Mvc\Layout to $Layout var
        $this->Layout = new Layout();
        // Load Mvc\Model to $Model var
        $this->Model = new Model();
        // Load Network\Http\Request helper to $Request var
        $this->Request = new Request();
        // Load Mvc\View to $View var
        $this->View = new View();
        // Set default view name
        $this->View->setName(Router::getAction());
    }
}