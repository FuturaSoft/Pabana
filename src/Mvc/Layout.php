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

class Layout
{
    private static $armVariable;
    private static $bAutoRender;
    private static $sDirectory;
    private static $sExtension;
    private static $sName;
    public $Html;
    public $View;

    public function __construct()
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
        $this->setName(Configuration::read('mvc.layout.default'), false);
    }

    public function __toString()
    {
        return $this->render();
    }

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
        return $this->element('index');
    }

    public function renderInit()
    {
        $this->Html->clean();
        $sAppNamespace = Configuration::read('application.namespace');
        $sLayoutNamespace = $sAppNamespace . '\Layout\\' . $this->getName();
        $oLayout = new $sLayoutNamespace();
        $oLayout->initialize();
    }

    public function setAutoRender($bAutoRender)
    {
        self::$bAutoRender = $bAutoRender;
    }

    public function setDirectory($sDirectory)
    {
        self::$sDirectory = $sDirectory;
    }

    public function setExtension($sExtension)
    {
        self::$sExtension = $sExtension;
    }

    public function setName($sName, $bReloadInit = true)
    {
        self::$sName = ucfirst($sName);
        if ($bReloadInit === true) {
            $this->renderInit();
        }
    }

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
