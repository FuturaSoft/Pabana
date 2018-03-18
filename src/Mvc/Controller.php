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
namespace Pabana\Mvc;

use Pabana\Core\Configuration;
use Pabana\Html\Html;
use Pabana\Mvc\Layout;
use Pabana\Mvc\Model;
use Pabana\Mvc\View;
use Pabana\Network\Http\Request;
use Pabana\Type\StringType;

/**
 * Controller class
 *
 * Launch controller and call Layout and View if defined
 */
class Controller
{
    /**
     * @var     Redirection to $html var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Html;

    /**
     * @var     Redirection to $layout var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Layout;

    /**
     * @var     Redirection to $model var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Model;

    /**
     * @var     Redirection to $request var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Request;

    /**
     * @var     Redirection to $view var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $View;

    /**
     * @var     \Pabana\Html\Html Object Html.
     * @since   1.1
     */
    public $html;

    /**
     * @var     \Pabana\Mvc\Layout Object Layout.
     * @since   1.1
     */
    public $layout;

    /**
     * @var     \Pabana\Mvc\Model Object Model.
     * @since   1.1
     */
    public $model;

    /**
     * @var     \Pabana\Network\Http\Request Object Request.
     * @since   1.1
     */
    public $request;

    /**
     * @var     \Pabana\Mvc\View Object View.
     * @since   1.1
     */
    public $view;

    /**
     * @var     string Controller name.
     * @since   1.1
     */
    private $controller;

    /**
     * Constructor
     *
     * Initialize Controller helper object (Html, Layout, Model, Request, View)
     *
     * @since   1.1
     * @return  void
     */
    final public function __construct()
    {
        $controllerNamespace = get_class($this);
        $controllerString = new StringType($controllerNamespace);
        // Get controller by current class name
        $this->controller = $controllerString->classBasename();
        $this->html = new Html();
        $this->model = new Model();
        $this->request = new Request();
        // To maintain compatibility with version 1.0
        $this->Html = $this->html;
        $this->Model = $this->model;
        $this->Request = $this->request;
    }

    /**
     * Create the View and the Layout
     * Call initialize method if exist
     * Launch controller
     * Get Layout and View render if enable
     *
     * @since   1.1
     * @return  string bodyContent of Controller, Layout and View
     */
    final public function render($action)
    {
        $this->setView($action);
        // Get Layout default name from Configuration
        $layoutName = Configuration::read('mvc.layout.default');
        $this->setLayout($layoutName);
        // Call initialize method in Controller if exists
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
        // Launch action of controller
        $bodyContent = $this->$action();
        // If Controller's Action return false, disable Layout and View
        if ($bodyContent !== false) {
            if ($this->layout->getAutoRender()) {
                // Get Layout and View if auto render of View enable
                $bodyContent .= $this->layout->render();
            } elseif ($this->view->getAutoRender()) {
                // Get View only
                $bodyContent .= $this->view->render();
            }
        }
        return $bodyContent;
    }

    /**
     * Call Layout object and store it in $this->layout variable
     *
     * @since   1.1
     * @param   string $layoutName Name of Layout
     * @return  void
     */
    final public function setLayout($layoutName)
    {
        $layoutNamespace = Configuration::read('mvc.layout.namespace');
        $layoutNamespace = $layoutNamespace . '\\' . $layoutName;
        $this->layout = new $layoutNamespace($this->view);
        // To maintain compatibility with version 1.0
        $this->Layout = $this->layout;
    }

    /**
     * Call Layout object and store it in $this->view variable
     * If Layout is already defined, change view of $this->layout
     *
     * @since   1.1
     * @param   string $action Target action name
     * @return  void
     */
    final public function setView($action)
    {
        $this->view = new View($action, $this->controller);
        // To maintain compatibility with version 1.0
        $this->View = $this->view;
        if (isset($this->layout)) {
            $this->layout->setView($this->view);
            // To maintain compatibility with version 1.0
            $this->Layout = $this->layout;
        }
    }
}
