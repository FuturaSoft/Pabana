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
use Pabana\Mvc\View;
use Pabana\Network\Http\Request;
use Pabana\Type\StringType;

/**
 * Layout class
 *
 * Manage Layout
 */
class Layout
{
    /**
     * @var     array Variable spooler to manage send var between controller and layout.
     * @since   1.0
     */
    private array $variableList = [];

    /**
     * @var     bool Define if autorender is enable.
     * @since   1.0
     */
    private bool $autoRender;

    /**
     * @var     string Define directory of Layout part.
     * @since   1.0
     */
    private string $directory;

    /**
     * @var     string Define extension of Layout part.
     * @since   1.0
     */
    private string $extension;

    /**
     * @var     string Define name of Layout.
     * @since   1.0
     */
    private string $name;

    /**
     * @var     \Pabana\Html\Html Object Html.
     * @since   1.1
     */
    public Html $html;

    /**
     * @var     \Pabana\Mvc\View Object View.
     * @since   1.1
     */
    public View $view;

    /**
     * @var     Redirection to $html var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public ?Html $Html = null;

    /**
     * @var     Redirection to $view var
     * @since   1.0
     * @deprecated deprecated since version 1.1
     */
    public ?View $View = null;

    /**
     * Initialize layout
     *
     * Load Html and View object
     * Define autorender, directory, extension, name from configuration
     *
     * @since   1.0
     * @param   \Pabana\Mvc\View $view View object
     * @param   bool $cleanHtml Reset Html object (by default false)
     * @return  void
     */
    public function __construct(View $view, bool $cleanHtml = false)
    {
        $layoutString = new StringType(get_class($this));
        // Get layout by current class name
        $layoutName = $layoutString->classBasename();
        $this->setName($layoutName);
        // Load Mvc\Html class to helper var
        $this->html = new Html();
        // Load Mvc\View class to helper var
        $this->view = $view;
        // To maintain compatibility with version 1.0
        $this->Html = $this->html;
        $this->View = $this->view;
        // Set auto render status from configuration
        $this->setAutoRender(Configuration::read('mvc.layout.auto_render'));
        // Set default directory for layout
        $directory = APP_ROOT . Configuration::read('mvc.layout.path');
        $layoutSuffix = Configuration::read('mvc.layout.suffix', '');
        $layoutDirectoryName = str_replace($layoutSuffix, '', $layoutString->classBasename());
        if (Configuration::read('mvc.layout.camal_to_snake', false) === true) {
            $layoutDirectoryNameString = new StringType($layoutDirectoryName);
            $layoutDirectoryName = $layoutDirectoryNameString->camalToSnake();
        }
        $directory .= DS . $layoutDirectoryName;
        $this->setDirectory($directory);
        // Set layout file extension from configuration
        $this->setExtension(Configuration::read('mvc.layout.extension'));
        if ($cleanHtml === true) {
            // Clean Html value
            $this->Html->clean();
        }
        // Read application namespace
        $this->initialize();
    }

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0
     * @return  string Html code for Layout
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
     * @since   1.0
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
        $layoutDirectory = $this->getDirectory();
        $elementPath = $layoutDirectory . DS . $elementName . '.' . $this->getExtension();
        if (!file_exists($elementPath)) {
            trigger_error('Element file "' . $elementPath . '" doesn\'t exist.', E_USER_ERROR);
            return false;
        }
        ob_start();
        require($elementPath);
        echo PHP_EOL;
        $bodyContent = ob_get_clean();
        return $bodyContent;
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
     * @return  string Layout part directory
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Get extension of layout file
     *
     * @since   1.0
     * @return  string Extension of layout file
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Get name of layout
     *
     * @since   1.0
     * @return  string Name of layout
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get var send to Layout from Controller
     *
     * @since   1.0
     * @param   string $varName Name of var send to Layout
     * @return  mixed Value of var send to Layout if exist else false
     */
    public function getVar(string $varName): mixed
    {
        if (!isset($this->variableList[$varName])) {
            trigger_error('Variable "' . $varName . '" isn\'t defined in Layout.', E_USER_WARNING);
            return false;
        }
        return $this->variableList[$varName];
    }

    /**
     * Render layout from index part
     *
     * @since   1.0
     * @return  string|bool Html code render or false if error
     */
    public function render(): string|false
    {
        return $this->element('index');
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
     * Set directory value of layout part
     *
     * @since   1.0
     * @param   string $directory Directory of layout part value
     * @return  void
     */
    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    /**
     * Set extension value for part file
     *
     * @since   1.0
     * @param   string $extension Extension value for part file
     * @return  void
     */
    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * Set name of layout and if define reload renderinit of layout
     *
     * @since   1.0
     * @param   string $name Name of Layout
     * @return  void
     */
    public function setName(string $name): void
    {
        $this->name = ucfirst($name);
    }

    /**
     * Set var to Layout
     *
     * @since   1.0
     * @param   string $varName Name of var send to Layout
     * @param   mixed $varValue Value of var send to Layout
     * @param   bool $force Force change of var value if var already exist
     * @return  bool Return true if success else false
     */
    public function setVar(string $varName, mixed $varValue, bool $force = false): bool
    {
        if (isset($this->variableList[$varName]) && $force === false) {
            trigger_error('Variable "' . $varName . '" is already defined in Layout.', E_USER_WARNING);
            return false;
        }
        $this->variableList[$varName] = $varValue;
        return true;
    }

    /**
     * Set view to Layout
     *
     * @since   1.1
     * @param   \Pabana\Mvc\View $view View object generated in controller
     * @return  void
     */
    public function setView(View $view): void
    {
        $this->view = $view;
        $this->View = $this->view;
    }
}
