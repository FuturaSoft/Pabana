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
use Pabana\Routing\Router;

/**
 * View class
 *
 * Manage View
 */
class View
{
    /**
     * @var     array Variable spooler to manage send var between controller, layout and view.
     * @since   1.0
     */
    private $variableList;

    /**
     * @var     bool Define if autorender is enable.
     * @since   1.0
     */
    private $autoRender;

    /**
     * @var     string Define directory of View.
     * @since   1.0
     */
    private $directory;

    /**
     * @var     string Define extension of View.
     * @since   1.0
     */
    private $extension;

    /**
     * @var     string Define name of View.
     * @since   1.0
     */
    private $name;

    /**
     * @var     Redirection to $html var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public $Html;

    /**
     * @var     \Pabana\Html\Html Object Html.
     * @since   1.1
     */
    public $html;

    /**
     * Initialize view
     *
     * Load Html object
     * Define autorender, directory, extension, name from configuration
     *
     * @since   1.0
     * @param   string $action Name of Action
     * @param   string $controller Name of Controller
     * @return  void
     */
    public function __construct($action, $controller)
    {
        // Load Mvc\Html helper to $html var
        $this->html = new Html();
        // To maintain compatibility with version 1.0
        $this->Html = $this->html;
        // Set auto render status from configuration
        $this->setAutoRender(Configuration::read('mvc.view.auto_render'));
        // Set default directory for view
        $viewRootPath = Configuration::read('application.path') . Configuration::read('mvc.view.path');
        $directoryPath = $viewRootPath . '/' . $controller;
        $this->setDirectory($directoryPath);
        // Set extension from configuration
        $this->setExtension(Configuration::read('mvc.view.extension'));
        // Set name of View
        $this->setName($action);
    }

    /**
     * Activate the render method
     *
     * @since   1.0
     * @return  string|bool View content if success or false if error
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get autorender state
     *
     * @since   1.0
     * @return  bool Autorender state
     */
    public function getAutoRender()
    {
        return $this->autoRender;
    }

    /**
     * Get directory path
     *
     * @since   1.0
     * @return  string View directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get extension of view file
     *
     * @since   1.0
     * @return  string Extension of view file
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get name of view
     *
     * @since   1.0
     * @return  string Name of view
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get var send to View from Controller or Layout
     *
     * @since   1.0
     * @param   string $varName Name of var send to View
     * @return  string|bool Value of var send to View if exist else false
     */
    public function getVar($varName)
    {
        if (!isset($this->variableList[$varName])) {
            trigger_error('Variable "' . $varName . '" isn\'t defined in View.', E_USER_WARNING);
            return false;
        }
        return $this->variableList[$varName];
    }

    /**
     * Render layout from view
     *
     * @since   1.0
     * @return  string|bool Return View content if success or false if error
     */
    public function render()
    {
        $viewPath = $this->getDirectory() . '/'. $this->getName() . '.' . $this->getExtension();
        if (!file_exists($viewPath)) {
            trigger_error('View file "' . $viewPath . '" doesn\'t exist.', E_USER_ERROR);
            return false;
        }
        if (Configuration::read('mvc.autoload_shared_var') && !empty($this->variableList)) {
            foreach ($this->variableList as $varName => $varValue) {
                ${$varName} = $varValue;
            }
        }
        ob_start();
        require($viewPath);
        echo PHP_EOL;
        return ob_get_clean();
    }

    /**
     * Set auto render value
     *
     * @since   1.0
     * @param   bool $autoRender Auto render value
     * @return  void
     */
    public function setAutoRender($autoRender)
    {
        $this->autoRender = $autoRender;
    }

    /**
     * Set directory of view
     *
     * @since   1.0
     * @param   string $directory Directory of view
     * @return  void
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * Set extension of view file
     *
     * @since   1.0
     * @param   string $extension Extension of view file
     * @return  void
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Set name of view file
     *
     * @since   1.0
     * @param   string $name Name of view file
     * @return  void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set var to View
     *
     * @since   1.0
     * @param   string $varName Name of var send to View
     * @param   string $varValue Value of var send to View
     * @param   bool $force Force change of var value if var already exist
     * @return  bool Return true
     */
    public function setVar($varName, $varValue, $force = false)
    {
        if (isset($this->variableList[$varName]) && $force === false) {
            trigger_error('Variable "' . $varName . '" is already defined in View.', E_USER_WARNING);
            return false;
        }
        $this->variableList[$varName] = $varValue;
        return true;
    }
}
