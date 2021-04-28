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
     * @since   1.0
     */
    private static $configList = array();

    /**
     * Get base configuration of Pabana
     *
     * This method defined default key and value for using Pabana
     *
     * @since   1.0
     * @return  void
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
        // Defined if bootstrap is use by application
        self::write('bootstrap.enable', true);
        // Set config file for route collection
        self::write('database.config.enable', true);
        // Set config file for route collection
        self::write('database.config.file', 'databases.php');
        // Set debug level to all
        self::write('debug.level', E_ALL);
        // Define if script file existence is tested
        self::write('html.script.test_file_existance', true);
        // Define if script version is automaticated add
        self::write('html.script.version', true);
        // Define if css file existence is tested
        self::write('html.css.test_file_existance', true);
        // Define if css version is automaticated add
        self::write('html.css.version', true);
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
     * @since   1.0
     * @param   string $key Key to check.
     * @return  bool True if key exists else false
     */
    public static function check($key)
    {
        return isset(self::$configList[$key]);
    }

    /**
     * Clean Configuration
     *
     * Delete all key and value in configuration
     * A parameter allow to reload base configuration
     *
     * @since   1.0
     * @param   bool $reloadBase If true reload base configuration
     * @return  bool Result of cleaning
     */
    public static function clean($reloadBase = true)
    {
        // Delete ALL key and value
        self::$configList = array();
        if ($reloadBase === true) {
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
     * @since   1.0
     * @param   string $key Key to delete.
     * @return  bool Result of delete Key
     */
    public static function delete($key)
    {
        // Check key existence
        if (!self::check($key)) {
            throw new \Exception('Configuration key "' . $key . '" doesn\'t exists');
            return false;
        }
        // Delete key and value
        unset(self::$configList[$key]);
        return true;
    }

    /**
     * Load a configuration file
     *
     * Load a configuration file
     *
     * @since   1.0
     * @param   string $filename File path of loaded file.
     * @param   bool $merge If true merge current config to new config.
     * @return  void
     */
    public static function load($filename, $merge = true)
    {
        // Check if file not exist
        if (!file_exists($filename)) {
            throw new Exception('Config file "' . $filename . '" doesn\'t exist.');
        }
        // Read extension of config file
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        // List allowed extension of file
        $allowedExtensionList = array('ini', 'json', 'php', 'xml');
        // Check if extension is recognized
        if (!in_array($fileExtension, $allowedExtensionList)) {
            throw new Exception('Config file "' . $filename . '" is in unrecognize format. Accepted format are ' . implode(', ', $allowedExtensionList) . '.');
        }
        // Load file and put in array
        if ($fileExtension === 'ini') {
            $iniConfig = new Ini();
            $iniConfig->load($filename);
            $configList = $iniConfig->toArray();
        } elseif ($fileExtension === 'json') {
            $jsonConfig = file_get_contents($filename);
            $configList = json_decode($jsonConfig, true);
        } elseif ($fileExtension === 'xml') {
            $xmlConfig = simplexml_load_file($filename);
            $jsonConfig = json_encode($xmlConfig);
            $configList = json_decode($jsonConfig, true);
        } else {
            $configList = (require $filename);
        }
        $preparedConfigList = self::prepareArray($configList);
        if ($merge === true) {
            self::$configList = $preparedConfigList + self::$configList;
        } else {
            self::$configList = $preparedConfigList;
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
     * @since   1.0
     * @param   string $key Key to prepare
     * @param   mixed $value Value to prepare.
     * @return  mixed Value prepared
     */
    public static function prepare($key, $value)
    {
        if ($value == 'true') {
            return true;
        } elseif ($value == 'false') {
            return false;
        } elseif ($key == 'debug.level' && substr($value, 0, 2) == "E_") {
            return eval('return ' . $value . ';');
        } elseif ($key == 'application.path' && ($value === false || $value === 'false' || $value === 'auto')) {
            $sLibraryPath = DS . 'vendor' . DS . 'pabana' . DS . 'pabana' . DS . 'src' . DS . 'Core';
            return str_replace($sLibraryPath, '', __DIR__);
        }
        return $value;
    }

    /**
     * Prepare a configuration array
     *
     * Parse array and prepare all of their value
     *
     * @since   1.0
     * @param   array $configList Array of key and value to prepare
     * @return  array Array of key and value prepared
     */
    public static function prepareArray($configList)
    {
        $preparedConfigList = array();
        foreach ($configList as $key => $value) {
            $preparedConfigList[$key] = self::prepare($key, $value);
        }
        return $preparedConfigList;
    }

    /**
     * Read a configuration key
     *
     * This method is used to read a key from Configuration
     * Key existance is checked first
     *
     * @since   1.0
     * @param   string $key Key to read.
     * @return  mixed|bool Value of Configuration parameter or false if configuration key doesn't exist
     */
    public static function read($key)
    {
        // Check key existence
        if (!self::check($key)) {
            throw new \Exception('Configuration key "' . $key . '" doesn\'t exists');
            return false;
        }
        // Get value of key
        return self::$configList[$key];
    }

    /**
     * Read all configuration collection
     *
     * This method is used to get collection of configuration
     *
     * @since   1.0
     * @return  array Array of all configuration
     */
    public static function readAll()
    {
        return self::$configList;
    }

    /**
     * Register constant
     *
     * This method is used to register constant
     *
     * @since   1.0
     * @return  void
     */
    public static function registerConstant()
    {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        if (!defined('PAB_NAME')) {
            define('PAB_NAME', 'Kiwi');
        }
        if (!defined('PAB_VERSION')) {
            define('PAB_VERSION', '1.1.0');
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
     * @since   1.0
     * @return  string Current version of Pabana
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
     * @since   1.0
     * @param   string $key Key to read.
     * @param   string $value Value of key.
     * @param   bool $force Force writing of key even is already defined.
     * @return  bool Return true if success else return false;.
     */
    public static function write($key, $value, $force = true)
    {
        // Check key existence
        if (self::check($key) && $force === false) {
            throw new \Exception('Configuration key "' . $key . '" already exist (use force argument to modify an already defined key).');
            return false;
        }
        // Prepare value (transform 'true' to true, etc...)
        $preparedValue = self::prepare($key, $value);
        // Write value content
        self::$configList[$key] = $preparedValue;
        return true;
    }
}
