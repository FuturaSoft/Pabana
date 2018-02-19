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
     * @since   1.0.0
     */
    private static $armVariable;

    /**
     * @var     bool Define if autorender is enable.
     * @since   1.0.0
     */
    private static $bAutoRender;

    /**
     * @var     string Define directory of View.
     * @since   1.0.0
     */
    private static $sDirectory;

    /**
     * @var     string Define extension of View.
     * @since   1.0.0
     */
    private static $sExtension;

    /**
     * @var     string Define name of View.
     * @since   1.0.0
     */
    private static $sName;

    /**
     * @var     \Pabana\Html\Html Object Html.
     * @since   1.0.0
     */
    public $Html;

    /**
     * Initialize view
     *
     * Load Html object
     * Define autorender, directory, extension, name from configuration
     *
     * @since   1.0.0
     * @return  void
     */
    public function __construct()
    {
        // Load Mvc\Html helper to $Html var
        $this->Html = new Html();
        // Set auto render status from configuration
        $this->setAutoRender(Configuration::read('mvc.view.auto_render'));
        // Set default directory for view
        $sDirectory = Configuration::read('application.path') . Configuration::read('mvc.view.path') . '/' . Router::getController();
        $this->setDirectory($sDirectory);
        // Set extension from configuration
        $this->setExtension(Configuration::read('mvc.view.extension'));
    }

    /**
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
     * @return  string View directory
     */
    public function getDirectory()
    {
        return self::$sDirectory;
    }

    /**
     * Get extension of view file
     *
     * @since   1.0.0
     * @return  string Extension of view file
     */
    public function getExtension()
    {
        return self::$sExtension;
    }

    /**
     * Get name of view
     *
     * @since   1.0.0
     * @return  string Name of view
     */
    public function getName()
    {
        return self::$sName;
    }

    /**
     * Get var send to view from controller or layout
     *
     * @since   1.0.0
     * @param   string $sVarName Name of var send to View
     * @return  string Value of var send to View
     */
    public function getVar($sVarName)
    {
        return self::$armVariable[$sVarName];
    }

    /**
     * Render layout from view
     *
     * @since   1.0.0
     * @return  string Html code render
     */
    public function render()
    {
        if (Configuration::read('mvc.autoload_shared_var') === true && empty(self::$armVariable) === false) {
            foreach (self::$armVariable as $sVarName => $sVarValue) {
                ${$sVarName} = $sVarValue;
            }
        }
        ob_start();
        require($this->getDirectory() . '/'. $this->getName() . '.' . $this->getExtension());
        $sHtmlView = ob_get_contents();
        ob_end_clean();
        return $sHtmlView;
    }

    /**
     * Set auto render value
     *
     * @since   1.0.0
     * @param   bool $bAutoRender Auto render value
     * @return  bool Return true
     */
    public function setAutoRender($bAutoRender)
    {
        self::$bAutoRender = $bAutoRender;
        return true;
    }

    /**
     * Set directory of view
     *
     * @since   1.0.0
     * @param   string $sDirectory Directory of view
     * @return  bool Return true
     */
    public function setDirectory($sDirectory)
    {
        self::$sDirectory = $sDirectory;
        return true;
    }

    /**
     * Set extension of view file
     *
     * @since   1.0.0
     * @param   string $sExtension Extension of view file
     * @return  bool Return true
     */
    public function setExtension($sExtension)
    {
        self::$sExtension = $sExtension;
        return true;
    }

    /**
     * Set name of view file
     *
     * @since   1.0.0
     * @param   string $sName Name of view file
     * @return  bool Return true
     */
    public function setName($sName)
    {
        self::$sName = $sName;
        return true;
    }

    /**
     * Set var to View
     *
     * @since   1.0.0
     * @param   string $sVarName Name of var send to View
     * @param   string $sVarValue Value of var send to View
     * @return  bool Return true
     */
    public function setVar($sVarName, $sVarValue)
    {
        self::$armVariable[$sVarName] = $sVarValue;
        return true;
    }
}