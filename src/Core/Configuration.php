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
namespace Pabana\Core;

use Pabana\Parser\Ini;

/**
 * Configuration class
 *
 * Generate, load and modify Pabana configuration
 */
class Configuration
{
    /**
     * @var    Array Array to store configuration parameters
     * @since   1.0.0
     */
    private static $armConfig = array();

    /**
     * Get base configuration of Pabana
     *
     * This method defined default key and value for using Pabana
     *
     * @since   1.0.0
     * @return 	void
     */
    public static function base()
    {
        // Charset by default
        self::write('application.encoding', 'UTF8');
        // Application environnement
        self::write('application.env', 'dev');
        // Application name
        self::write('application.name', 'Awesome Application');
        // Namespace for application
        self::write('application.namespace', '\App');
        // Absolute path to application
        self::write('application.path', 'auto');
        // Set debug level to all
        self::write('debug.level', E_ALL);
        // Set autoloading of shared var between componant
        self::write('mvc.autoload_shared_var', true);
        // Set namespace for controller
        self::write('mvc.controller.namespace', '\App\Controller');
        // Set namespace for layout
        self::write('mvc.layout.namespace', '\App\Layout');
        // Set path for Layout
        self::write('mvc.layout.path', '/src/Layout');
        // Set default layout
        self::write('mvc.layout.default', 'Application');
        // Set extension for layout
        self::write('mvc.layout.extension', 'php');
        // Set auto render for layout
        self::write('mvc.layout.auto_render', true);
        // Set namespace for model
        self::write('mvc.model.namespace', '\App\Model');
        // Set auto render for view
        self::write('mvc.view.auto_render', true);
        // Set extension for view
        self::write('mvc.view.extension', 'php');
        // Set path for view
        self::write('mvc.view.path', '/src/View');
        // Set auto routing
        self::write('routing.auto', true);
        // Set config file for route collection
        self::write('routing.config.enable', true);
        // Set config file for route collection
        self::write('routing.config.file', 'routes.php');
        // Set error action (if not route is avaiable)
        self::write('routing.fallback.action', 'index');
        // Set error controller (if not route is avaiable)
        self::write('routing.fallback.controller', 'Error');
        // Set config file for route collection
        self::write('routing.default_separator', '/');
    }

    /**
     * Check if a configuration key exists
     *
     * This method is used to check if a key exists in Configuration
     *
     * @since   1.0.0
     * @param 	string $sKey Key to check.
     * @return 	bool True if key exists else false
     */
    public static function check($sKey)
    {
        return isset(self::$armConfig[$sKey]);
    }

    /**
     * Clean Configuration
     *
     * Delete all key and value in configuration
     * A parameter allow to reload base configuration
     *
     * @since   1.0.0
     * @param 	bool $bReloadBase If true reload base configuration
     * @return 	bool Result of cleaning
     */
    public static function clean($bReloadBase = true)
    {
        // Delete ALL key and value
        self::$armConfig = array();
        if ($bReloadBase === true) {
            // Reload base configuration
            self::base();
        }
    }

    /**
     * Delete a configuration key
     *
     * This method is used to delete a key from Configuration
     * Key existance is checked first
     *
     * @since   1.0.0
     * @param 	string $sKey Key to delete.
     * @return 	bool Result of delete Key
     */
    public static function delete($sKey)
    {
        // Check key existence
        if (!self::check($sKey)) {
            throw new Error('Configuration key "' . $sKey . '" doesn\'t exists');
            return false;
        }
        // Delete key and value
        unset(self::$armConfig[$sKey]);
        return true;
    }

    /**
     * Load a configuration file
     *
     * Load a configuration file
     *
     * @since   1.0.0
     * @param 	string $sFilename File path of loaded file.
     * @param   bool $bMerge If true merge current config to new config.
     * @return 	void
     */
    public static function load($sFilename, $bMerge = true)
    {
        // Check if file not exist
        if (!file_exists($sFilename)) {
            throw new Exception('Config file "' . $sFilename . '" doesn\'t exist.');
        }
        // Read extension of config file
        $sFiletype = pathinfo($sFilename, PATHINFO_EXTENSION);
        // List allowed extension of file
        $arsAllowedFiletype = array('ini', 'json', 'php', 'xml');
        // Check if extension is recognized
        if (!in_array($sFiletype, $arsAllowedFiletype)) {
            throw new Exception('Config file "' . $sFilename . '" is in unrecognize format. Accepted format are ' . implode(', ', $arsAllowedFiletype) . '.');
        }
        // Load file and put in array
        if ($sFiletype === 'ini') {
            $oIniConfig = new Ini();
            $oIniConfig->load($sFilename);
            $armConfig = $oIniConfig->toArray();
        } elseif ($sFiletype === 'json') {
            $sJson = file_get_contents($sFilename);
            $armConfig = json_decode($sJson, true);
        } elseif ($sFiletype === 'xml') {
            $oXmlConfig = simplexml_load_file($sFilename);
            $oJsonConfig = json_encode($oXmlConfig);
            $armConfig = json_decode($oJsonConfig, true);
        } else {
            $armConfig = (require $sFilename);
        }
        $armConfigPrepare = self::prepareArray($armConfig);
        if ($bMerge === true) {
            self::$armConfig = $armConfigPrepare + self::$armConfig;
        } else {
            self::$armConfig = $armConfigPrepare;
        }
        // Register constant who aren't register by the past
        self::registerConstant();
    }

    /**
     * Prepare a configuration value
     *
     * This method is used to modify a configuration value
     * For exemple change 'true' string to true boolean
     *
     * @since   1.0.0
     * @param 	string $sKey Key to prepare
     * @param 	mixed $mValue Value to prepare.
     * @return 	mixed Value prepared
     */
    public static function prepare($sKey, $mValue)
    {
        if ($mValue == "true") {
            return true;
        } elseif ($mValue == "false") {
            return false;
        } elseif ($sKey == 'debug.level' && substr($mValue, 0, 2) == "E_") {
            return eval('return ' . $mValue . ';');
        } elseif ($sKey == 'application.path' && ($mValue === false || $mValue === 'false' || $mValue === 'auto')) {
            $sLibraryPath = DS . 'vendor' . DS . 'pabana' . DS . 'pabana' . DS . 'src' . DS . 'Core';
            return str_replace($sLibraryPath, '', __DIR__);
        }
        return $mValue;
    }

    /**
     * Prepare a configuration array
     *
     * Parse array and prepare all of their value
     *
     * @since   1.0.0
     * @param 	array $armArray Array of key and value to prepare
     * @return 	array Array of key and value prepared
     */
    public static function prepareArray($armArray)
    {
        $armArrayPrepare = array();
        foreach ($armArray as $sKey => $mValue) {
            $armArrayPrepare[$sKey] = self::prepare($sKey, $mValue);
        }
        return $armArrayPrepare;
    }

    /**
     * Read a configuration key
     *
     * This method is used to read a key from Configuration
     * Key existance is checked first
     *
     * @since   1.0.0
     * @param 	string $sKey Key to read.
     * @return 	mixed|bool Value of Configuration parameter or false if configuration key doesn't exist
     */
    public static function read($sKey)
    {
        // Check key existence
        if (!self::check($sKey)) {
            throw new \Exception('Configuration key "' . $sKey . '" doesn\'t exists');
            return false;
        }
        // Get value of key
        return self::$armConfig[$sKey];
    }

    /**
     * Read all configuration collection
     *
     * This method is used to get collection of configuration
     *
     * @since   1.0.0
     * @return 	array Array of all configuration
     */
    public static function readAll()
    {
        return self::$armConfig;
    }

    /**
     * Register constant
     *
     * This method is used to register constant
     *
     * @since   1.0.0
     * @return  void
     */
    public static function registerConstant()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        if (!defined('PAB_NAME')) {
            define('PAB_NAME', 'Banana');
        }
        if (!defined('PAB_VERSION')) {
            define('PAB_VERSION', '1.0.6');
        }
        if (self::check('application.path') === true) {
            if (!defined('APP_ROOT')) {
                define('APP_ROOT', self::read('application.path'));
            }
        }
    }

    /**
     * Get Pabana version
     *
     * Return current version of Pabana
     *
     * @since   1.0.0
     * @return 	string Current version of Pabana
     */
    public static function version()
    {
        return PAB_VERSION;
    }

    /**
     * Write a configuration key
     *
     * This method is used to write a key and a value
     * First value is prepare by prepare method
     *
     * @since   1.0.0
     * @param 	string $sKey Key to read.
     * @param 	string $mValue Value of key.
     * @return 	void
     */
    public static function write($sKey, $mValue)
    {
        // Prepare value (transform 'true' to true, etc...)
        $mValue = self::prepare($sKey, $mValue);
        // Write value content
        self::$armConfig[$sKey] = $mValue;
    }
}
