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
use Pabana\Network\Http\Request;
use Pabana\Routing\Router;
use Pabana\Type\StringType;

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
    private array $variableList = [];

    /**
     * @var     bool Define if autorender is enable.
     * @since   1.0
     */
    private bool $autoRender;

    /**
     * @var     string Define directory of View.
     * @since   1.0
     */
    private string $directory;

    /**
     * @var     string Define extension of View.
     * @since   1.0
     */
    private string $extension;

    /**
     * @var     string Define name of View.
     * @since   1.0
     */
    private string $name;

    /**
     * @var     Redirection to $html var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public ?Html $Html = null;

    /**
     * @var     \Pabana\Html\Html Object Html.
     * @since   1.1
     */
    public Html $html;

    /**
     * @var     \Pabana\Network\Http\Request Object Request.
     * @since   1.2
     */
    public Request $request;

    /**
     * Initialize view
     *
     * Load Html object
     * Define autorender, directory, extension, name from configuration
     *
     * @since   1.0
     * @param   string $controller Name of Controller
     * @param   string $action Name of Action
     * @return  void
     */
    public function __construct(string $controller, string $action)
    {
        // Load Mvc\Html helper to $html var
        $this->html = new Html();
        // To maintain compatibility with version 1.0
        $this->Html = $this->html;
        // Load Http\Request helper to $html var
        $this->request = new Request();
        // Set auto render status from configuration
        $this->setAutoRender(Configuration::read('mvc.view.auto_render'));
        // Set default directory for view
        $viewRootPath = Configuration::read('application.path') . Configuration::read('mvc.view.path');
        $controllerSuffix = Configuration::read('mvc.controller.suffix', '');
        $directoryPath = str_replace($controllerSuffix, '', $controller);
        if (Configuration::read('mvc.view.camal_to_snake', false) === true) {
            $directoryPathString = new StringType($directoryPath);
            $directoryPath = $directoryPathString->camalToSnake();
        }
        $fullDirectoryPath = $viewRootPath . DS . $directoryPath;
        $this->setDirectory($fullDirectoryPath);
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
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Load part of Layout
     *
     * Load Html code of part
     *
     * @since   1.2
     * @param   string $elementName Element or part name
     * @return  string|bool Return Element content if success or false if error
     */
    public function element(string $elementName): string|false
    {
        if (Configuration::read('mvc.autoload_shared_var') === true && empty($this->variableList) === false) {
            foreach ($this->variableList as $varName => $varValue) {
                ${$varName} = $varValue;
            }
        }
        $layoutDirectory = $this->getDirectory() . '/element';
        $elementPath = $layoutDirectory . '/' . $elementName . '.' . $this->getExtension();
        if (!file_exists($elementPath)) {
            trigger_error('Element file "' . $elementPath . '" doesn\'t exist.', E_USER_ERROR);
            return false;
        }
        ob_start();
        require($elementPath);
        echo PHP_EOL;
        $content = ob_get_clean();
        return $content;
    }

    /**
     * Get autorender state
     *
     * @since   1.0
     * @return  bool Autorender state
     */
    public function getAutoRender(): bool
    {
        return $this->autoRender;
    }

    /**
     * Get directory path
     *
     * @since   1.0
     * @return  string View directory
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Get extension of view file
     *
     * @since   1.0
     * @return  string Extension of view file
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Get name of view
     *
     * @since   1.0
     * @return  string Name of view
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get var send to View from Controller or Layout
     *
     * @since   1.0
     * @param   string $varName Name of var send to View
     * @return  mixed Value of var send to View if exist else false
     */
    public function getVar(string $varName): mixed
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
    public function render(): string|false
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
    public function setAutoRender(bool $autoRender): void
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
    public function setDirectory(string $directory): void
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
    public function setExtension(string $extension): void
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
    public function setName(string $name): void
    {
        if (Configuration::read('mvc.view.camal_to_snake', false) === true) {
            $nameString = new StringType($name);
            $name = $nameString->camalToSnake();
        }
        $this->name = $name;
    }

    /**
     * Set var to View
     *
     * @since   1.0
     * @param   string $varName Name of var send to View
     * @param   mixed $varValue Value of var send to View
     * @param   bool $force Force change of var value if var already exist
     * @return  bool Return true if success else false
     */
    public function setVar(string $varName, mixed $varValue, bool $force = false): bool
    {
        if (isset($this->variableList[$varName]) && $force === false) {
            trigger_error('Variable "' . $varName . '" is already defined in View.', E_USER_WARNING);
            return false;
        }
        $this->variableList[$varName] = $varValue;
        return true;
    }
}
