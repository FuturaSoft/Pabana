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

class View
{
    private static $armVariable;
    private static $bAutoRender;
    private static $sDirectory;
    private static $sExtension;
    private static $sName;
    public $Html;

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

    public function __toString()
    {
        return $this->render();
    }

    public function getAutoRender()
    {
        return self::$bAutoRender;
    }

    public function getDirectory()
    {
        return self::$sDirectory;
    }

    public function getExtension()
    {
        return self::$sExtension;
    }

    public function getName()
    {
        return self::$sName;
    }

    public function getVar($sVarName)
    {
        return self::$armVariable[$sVarName];
    }

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

    public function setAutoRender($bAutoRender)
    {
        self::$bAutoRender = $bAutoRender;
        return true;
    }

    public function setDirectory($sDirectory)
    {
        self::$sDirectory = $sDirectory;
        return true;
    }

    public function setExtension($sExtension)
    {
        self::$sExtension = $sExtension;
        return true;
    }

    public function setName($sName)
    {
        self::$sName = $sName;
        return true;
    }

    public function setVar($sVarName, $sVarValue)
    {
        self::$armVariable[$sVarName] = $sVarValue;
        return true;
    }
}
