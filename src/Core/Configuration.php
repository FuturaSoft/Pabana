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

use Pabana\Debug\Error;
use Pabana\Parser\Ini;
use Pabana\Type\Collection;

/**
 * Configuration class
 *
 * Generate, load and modify Pabana configuration
 */
class Configuration
{
	/**
	 * Array to store configuration parameters
	 *
	 * @var Type\Collection
	 */
    private static $oColConfig;

    /**
     * Get base configuration of Pabana
     *
     * This method defined default key and value for using Pabana
     *
     * @since   1.0.0
     * @return 	void
     */
    public static function base($bCreateCollection = true)
    {
    	if ($bCreateCollection === true) {
	    	// Store new Collection object
	    	self::$oColConfig = new Collection();
	    }
    	// Absolute path to application
        self::write('application.path', 'auto');
        // Application environnement
        self::write('application.env', 'dev');
        // Set debug level to all
        self::write('debug.level', E_ALL);
        // Set intl
        self::write('intl.enable', false);
        // Set current locale
        self::write('intl.locale', locale_get_default());
        // Set fallback locale
        self::write('intl.fallback', locale_get_default());
        // Set default namespace for bootstrap
        self::write('mvc.bootstrap.namespace', '\Application\Bootstrap');
        // Set default namespace for controller
        self::write('mvc.controller.namespace', '\Application\Controller');
        // Set error controller name
        self::write('mvc.controller.error', 'Error');
        // Set default namespace for layout
        self::write('mvc.layout.namespace', '\Application\Layout');
        // Set path for Layout
        self::write('mvc.layout.path', '/application/Layout');
        // Set default name for layout
        self::write('mvc.layout.default', 'Default');
        // Set auto render for layout
        self::write('mvc.layout.auto_render', true);
        // Set default namespace for model
        self::write('mvc.model.namespace', '\Application\Model');
        // Set default extension for view
        self::write('mvc.view.extension', 'php');
        // Set default path for view
        self::write('mvc.view.path', '/application/View');
        // Set auto render for view
        self::write('mvc.view.auto_render', true);
        // Set auto routing
        self::write('routing.auto', true);
        // Set config file for route collection
        self::write('routing.config_file', '/config/routing.php');
        // Set default separator for router
        self::write('routing.separator', '/');
    }

    /**
     * Check if a configuration key exists
     *
     * This method is used to check if a key exists in Configuration
     *
     * @since   1.0.0
     * @param 	string $key Key to check.
     * @return 	bool True if key exists else false
     */
    public static function check($sKey)
    {
    	// Check existence of key
        return self::$oColConfig->exists($sKey);
    }

    /**
     * Clean Configuration
     *
     * Delete all key and value in configuration
     * A parameter allow to reload base configuration
     *
     * @since   1.0.0
     * @param 	bool $reloadBase If true reload base configuration
     * @return 	bool Result of cleaning
     */
    public static function clean($bReloadBase = true)
    {
        if ($bReloadBase === true) {
        	// Delete ALL key and value and reload base configuration
        	return self::base();
        } else {
        	// Delete ALL key and value
        	return self::$oColConfig->clean();
        }
    }

    /**
     * Delete a configuration key
     *
     * This method is used to delete a key from Configuration
     * Key existance is checked first
     *
     * @since   1.0.0
     * @param 	string $key Key to delete.
     * @return 	bool Result of delete Key
     */
    public static function delete($sKey)
    {
    	// Check key existence
    	if (!self::check($sKey)) {
    		throw new Error('Configuration key"' . $sKey . '" doesn\'t exists');
    		return false;
    	}
    	// Delete key and value
        return self::$oColConfig->remove($sKey);
    }

    /**
     * Delete a configuration key
     *
     * This method is used to delete a key from Configuration
     * Key existance is checked first
     *
     * @since   1.0.0
     * @param 	string $key Key to delete.
     * @return 	bool Result of delete Key
     */
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
        $arsAllowedFiletype = array('ini', 'json', 'php', 'xml');
        // Check if extension is recognized
        if (!in_array($sFiletype, $arsAllowedFiletype)) {
            throw new Error('Config file "' . $sFilename . '" is in unrecognize format. Accepted format are ' . implode(', ', $arsAllowedFiletype) . '.');
        }
        // Load file and put in array
        if ($sFiletype === 'ini') {
            $oIniConfig = new Ini();
            $oIniConfig->load($sFilename);
            $armConfig = $oIniConfig->toArray();
        } else if($sFiletype === 'json') {
            $sJson = file_get_contents($sFilename);
            $armConfig = json_decode($sJson, true);
        } else if($sFiletype === 'xml') {
            $oXmlConfig = simplexml_load_file($sFilename);
            $oJsonConfig = json_encode($oXmlConfig);
            $armConfig = json_decode($oJsonConfig, true);
        } else {
            $armConfig = (include $sFilename);
        }
        $armConfigPrepare = self::prepareArray($armConfig);
        $oColFileConfig = new Collection($armConfigPrepare);
        if ($bMerge === true) {
            self::merge($oColFileConfig);
        } else {
        	self::$oColConfig = $oColFileConfig;
        }
    }

    /**
     * Merge current configuration with another collection
     *
     * This method merge current collection with another collection
     *
     * @since   1.0.0
     * @param 	Type\Collection $fileConfig New collection
     * @return 	void
     */
    public static function merge($oColFileConfig)
    {
    	self::$oColConfig->merge($oColFileConfig);
    }

    /**
     * Prepare a configuration value
     *
     * This method is used to modify a configuration value
     * For exemple change 'true' to true
     *
     * @since   1.0.0
     * @param 	string $sKey Key to prepare
     * @param 	mixed $mValue Value to prepare.
     * @return 	mixed Value prepared
     */
    public static function prepare($sKey, $mValue)
    {
    	if($mValue == "true") {
            return true;
        } else if($mValue == "false") {
            return false;
        } else if($sKey == 'debug.level' && substr($mValue, 0, 2) == "E_") {
            return eval('return ' . $mValue . ';');
        } else if($sKey == 'application.path' && ($mValue === false || $mValue === 'false' || $mValue === 'auto')) {
        	$sLibraryPath = DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'pabana';
        	$sLibraryPath .= DIRECTORY_SEPARATOR . 'pabana' . DIRECTORY_SEPARATOR . 'src' .DIRECTORY_SEPARATOR . 'Core';
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
     * @param 	string $array Key to prepare
     * @return 	array Array prepared
     */
    public static function prepareArray($armArray)
    {
    	$armArrayPrepare = array();
    	foreach($armArray as $sKey => $mValue) {
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
     * @param 	string $key Key to read.
     * @return 	mixed Value of Configuration parameter
     */
    public static function read($sKey)
    {
    	// Check key existence
    	if (!self::check($sKey)) {
    		throw new Error('Configuration key"' . $sKey . '" doesn\'t exists');
    		return false;
    	}
    	// Get value of key
        return self::$oColConfig->get($sKey);
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
        return self::$oColConfig->getAll();
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
        return PE_VERSION;
    }

    /**
     * Write a configuration key
     *
     * This method is used to write a key and a value
     * First value is prepare by prepare method
     *
     * @since   1.0.0
     * @param 	string $key Key to read.
     * @param 	string $value Value of key.
     * @return 	bool True if write work else false
     */
    public static function write($sKey, $mValue)
    {
    	// Prepare value (transform 'true' to true, etc...)
    	$mValue = self::prepare($sKey, $mValue);
    	// Write value content
        return self::$oColConfig->set($sKey, $mValue);
    }
}