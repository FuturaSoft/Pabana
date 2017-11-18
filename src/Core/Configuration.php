<?php
namespace Pabana\Core;

use Pabana\Debug\Error;
use Pabana\Parser\Ini;
use Pabana\Type\Array;

class Configuration
{
    private static $armValues = new Array();

    public static function add($sName, $mValue)
    {
        if(self::$armValues->exists($sName)) {
            throw new Error('Configuration parameters "' . $sName . '" already exists.');
            return false;
        }
        return self::$armValues->set($sName, $mValue);
    }

    public static function clean($sName)
    {
        self::$_armValues = null;
        self::base();
        return true;
    }

    public static function base()
    {
        $sLibraryPath = DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'pabana' . DIRECTORY_SEPARATOR . 'pabana' . DIRECTORY_SEPARATOR . 'src' .DIRECTORY_SEPARATOR . 'Core';
        $sApplicationPath = str_replace($sLibraryPath, '', __DIR__);
        self::set('application.path', $sApplicationPath);
        self::set('application.env', 'dev');
        // Set default namespace for bootstrap
        self::set('bootstrap.namespace', '\Application\Bootstrap');
        // Set default namespace for controller
        self::set('controller.namespace', '\Application\Controller');
        // Set error controller name
        self::set('controller.error', 'Error');
        // Set debug level to all
        self::set('debug.level', E_ALL);
        // Set intl
        self::set('intl.enable', false);
        self::set('intl.locale', locale_get_default());
        self::set('intl.fallback', locale_get_default());
        // Set default namespace for layout
        self::set('layout.namespace', '\Application\Layout');
        // Set path for Layout
        self::set('layout.path', '/application/Layout');
        // Set default name for layout
        self::set('layout.default', 'Default');
        // Set auto render for layout
        self::set('layout.auto_render', true);
        // Set default namespace for model
        self::set('model.namespace', '\Application\Model');
        // Set config file for route collection
        self::set('router.config_file', '/config/routing.php');
        // Set default separator for router
        self::set('router.separator', '/');
        // Set default namespace for view
        self::set('view.extension', 'php');
        self::set('view.path', '/application/View');
        // Set auto render for view
        self::set('view.auto_render', true);
    }

    public static function exists($sName)
    {
        if(isset(self::$armValues[$sName])) {
            return true;
        } else {
            return false;
        }
    }

    public static function get($sName)
    {
        return self::$armValues[$sName];
    }
	
	public static function getAll()
    {
        return self::$armValues;
    }

    public static function load($sFilename, $sFiletype = false, $bMerge = true)
    {
        // Check if file not exist
        if (!file_exists($sFilename)) {
            throw new Error('Config file "' . $sFilename . '" doesn\'t exist.');
        }
        // Read extension of config file
        if ($sFiletype === false || $sFiletype === 'auto') {
            $sFiletype = pathinfo($sFilename, PATHINFO_EXTENSION);
        }
        // List allowed extension of file
        $arsAllowedFiletype = array('xml', 'ini', 'php');
        // Check if extension is recognized
        if (!in_array($sFiletype, $arsAllowedFiletype)) {
            throw new Error('Config file "' . $sFilename . '" is in unrecognize format. Accepted format are ' . implode(', ', $arsAllowedFiletype) . '.');
        }
        // Load file and put in array
        if($sFiletype === 'xml') {
            $oXmlConfig = simplexml_load_file($sFilename);
            $oJsonConfig = json_encode($oXmlConfig);
            $arsConfig = json_decode($oJsonConfig, true);
        } else if ($sFiletype === 'ini') {
            $oIniConfig = new Ini();
            $oIniConfig->load($sFilename);
            $arsConfig = $oIniConfig->toArray();
        } else {
            $arsConfig = (include $sFilename);
        }
        if ($bMerge === true) {
            
        }
    }

    public static function remove($sName)
    {
        if(!self::exists($sName)) {
            throw new Error('Configuration parameters "' . $sName . '" not exists.');
            return false;
        }
        self::$armValues[$sName] = null;
        return true;
    }

    public static function set($sName, $mValue)
    {
        $mValue = self::specialValue($sName, $mValue);
        self::$armValues[$sName] = $mValue;
        return true;
    }

    public static function setAll($arsConfig)
    {
        self::$armValues = $arsConfig + self::$armValues;
        return true;
    }

    public static function version()
    {
        return PE_VERSION;
    }

    private static function specialValue($sName, $mValue)
    {
        if($mValue == "true") {
            return true;
        } else if($mValue == "false") {
            return false;
        } else if($sName == 'debug.level' && substr($mValue, 0, 2) == "E_") {
            return eval($mValue);
        }
        return $mValue;
    }
}
?>