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
use Pabana\Mvc\View;
use Pabana\Network\Http\Request;

/**
 * Layout class
 *
 * Manage Layout
 */
class Layout
{
    /**
     * @var     array Variable spooler to manage send var between controller and layout.
     * @since   1.0.0
     */
    private static $armVariable;

    /**
     * @var     bool Define if autorender is enable.
     * @since   1.0.0
     */
    private static $bAutoRender;

    /**
     * @var     string Define directory of Layout part.
     * @since   1.0.0
     */
    private static $sDirectory;

    /**
     * @var     string Define extension of Layout part.
     * @since   1.0.0
     */
    private static $sExtension;

    /**
     * @var     string Define name of Layout.
     * @since   1.0.0
     */
    private static $sName;

    /**
     * @var     \Pabana\Html\Html Object Html.
     * @since   1.0.0
     */
    public $Html;

    /**
     * @var     \Pabana\Mvc\View Object View.
     * @since   1.0.0
     */
    public $View;

    /**
     * Initialize controller
     *
     * Load Html and View object
     * Define autorender, directory, extension, name from configuration
     *
     * @since   1.0.0
     * @param   string $sLayoutName Name of Layout loaded
     * @return  void
     */
    public function __construct($sLayoutName = null)
    {
        // Load Mvc\Html helper to $Html var
        $this->Html = new Html();
        // Load Mvc\View helper to $View var
        $this->View = new View();
        // Set auto render status from configuration
        $this->setAutoRender(Configuration::read('mvc.layout.auto_render'));
        // Set default directory for view
        $sDirectory = Configuration::read('application.path') . Configuration::read('mvc.layout.path');
        $this->setDirectory($sDirectory);
        // Set extension from configuration
        $this->setExtension(Configuration::read('mvc.layout.extension'));
        // Set default layout name
        if (empty($sLayoutName)) {
            $sLayoutName = Configuration::read('mvc.layout.default');
        }
        $this->setName($sLayoutName, false);
    }

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code for Layout
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Load part of Layout
     *
     * Load Html code of part
     *
     * @since   1.0.0
     * @param  string $sElement Element or part name
     * @return  string Html code of part
     */
    public function element($sElement)
    {
        if (Configuration::read('mvc.autoload_shared_var') === true && empty(self::$armVariable) === false) {
            foreach (self::$armVariable as $sVarName => $sVarValue) {
                ${$sVarName} = $sVarValue;
            }
        }
        $sPath = $this->getDirectory() . '/' . $this->getName() . '/' . $sElement . '.' . $this->getExtension();
        ob_start();
        require($sPath);
        echo PHP_EOL;
        $sHtml = ob_get_contents();
        ob_end_clean();
        return $sHtml;
    }

    /**
     * Get autorender state
     *
     * @since   1.0.0
     * @return  bool Autorender state
     */
    public function getAutoRender()
    {
        return self::$bAutoRender;
    }

    /**
     * Get directory path
     *
     * @since   1.0.0
     * @return  string Layout part directory
     */
    public function getDirectory()
    {
        return self::$sDirectory;
    }

    /**
     * Get extension of layout file
     *
     * @since   1.0.0
     * @return  string Extension of layout file
     */
    public function getExtension()
    {
        return self::$sExtension;
    }

    /**
     * Get name of layout
     *
     * @since   1.0.0
     * @return  string Name of layout
     */
    public function getName()
    {
        return self::$sName;
    }

    /**
     * Get var send to layout from controller
     *
     * @since   1.0.0
     * @param  string $sVarName Name of var send to Layout
     * @return  string Value of var send to Layout
     */
    public function getVar($sVarName)
    {
        return self::$armVariable[$sVarName];
    }

    /**
     * Render layout from index part
     *
     * @since   1.0.0
     * @return  string Html code render
     */
    public function render()
    {
        return $this->element('index');
    }

    /**
     * Initialize render by calling Layout user defined class
     *
     * @since   1.0.0
     * @return  void
     */
    public function renderInit()
    {
        // Clean Html value
        $this->Html->clean();
        // Read application namespace
        $sAppNamespace = Configuration::read('application.namespace');
        $sLayoutNamespace = $sAppNamespace . '\Layout\\' . $this->getName();
        $oLayout = new $sLayoutNamespace($this->getName());
        $oLayout->initialize();
    }

    /**
     * Set auto render value
     *
     * @since   1.0.0
     * @param   bool $bAutoRender Auto render value
     * @return  void
     */
    public function setAutoRender($bAutoRender)
    {
        self::$bAutoRender = $bAutoRender;
    }

    /**
     * Set directory value of layout part
     *
     * @since   1.0.0
     * @param   string $sDirectory Directory of layout part value
     * @return  void
     */
    public function setDirectory($sDirectory)
    {
        self::$sDirectory = $sDirectory;
    }

    /**
     * Set extension value for part file
     *
     * @since   1.0.0
     * @param   string $sExtension Extension value for part file
     * @return  void
     */
    public function setExtension($sExtension)
    {
        self::$sExtension = $sExtension;
    }

    /**
     * Set name of layout and if define reload renderinit of layout
     *
     * @since   1.0.0
     * @param   string $sName Name of Layout
     * @param   bool $bReloadInit Reload renderInit method after change name of Layout
     * @return  void
     */
    public function setName($sName, $bReloadInit = true)
    {
        self::$sName = ucfirst($sName);
        if ($bReloadInit === true) {
            $this->renderInit();
        }
    }

    /**
     * Set var to Layout
     *
     * @since   1.0.0
     * @param   string $sVarName Name of var send to Layout
     * @param   string $sVarValue Value of var send to Layout
     * @return  bool Return true if success else false 
     */
    public function setVar($sVarName, $sVarValue)
    {
        if (!isset(self::$armVariable[$sVarName])) {
            self::$armVariable[$sVarName] = $sVarValue;
            return true;
        } else {
            throw new \Exception("Variable " . $sVarName . " is already defined in Layout");
            return false;
        }
    }
}
