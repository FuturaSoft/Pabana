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

use Pabana\Core\Configuration;
use Pabana\Error\Error;
use Pabana\Router\Router;

/**
 * Pabana version
 * @var string
 */
const PE_VERSION = '1.0.0';

/**
 * Minimum version of PHP required for usage of Pabana
 * @var string
 */
const PE_PHP_MIN_VERSION = '5.6.1';

/**
* Core class
*
* This class is used to initialize Pabana and start MVC
*/
class Core
{
	/**
     * Start Pabana by check PHP version and load default config
	 */
	public function __construct()
	{
		// Check if current version of PHP can be use by Pabana
		$this->checkPhpVersion();
		// Store default settings of Pabana
		Configuration::base();
    }
	
	/**
     * Check if current version of PHP can be use by Pabana
	 */
	private function checkPhpVersion()
	{
		// Compare current PHP version with min require version of PHP for Pabana
		if (version_compare(PHP_VERSION, PE_PHP_MIN_VERSION, '<')) {
			// If current PHP version is less than require version, show error
			$sErrorMessage = 'Your PHP version "' . PHP_VERSION . '" is less than';
			$sErrorMessage .= ' require version of PHP "' . PE_PHP_MIN_VERSION . '" to use Pabana';
			throw new Error($sErrorMessage);
		}
	}
	
	/**
     * Launch router, then bootstrap and after MVC
	 */
	public function run()
	{
		// Start routing
		$oRouter = new Router();
		$oRouter->resolve();
		// Load bootstrap file
		$sBootstrapNamespace = Configuration::get('bootstrap.namespace');
		$oBootstrap = new $sBootstrapNamespace();
		$oBootstrap->initialize();
		// Start controller
		$sControllerName = ucfirst($oRouter::$controller);
		$sControllerNamespace = Configuration::get('controller.namespace') . '\\' . $sControllerName;
		$sAction = lcfirst($oRouter::$action);
		$oController = new $sControllerNamespace();
		$oController->init();
		if(method_exists($oController, 'initialize')) {
			$oController->initialize();
		}
		$oController->$sAction();
		// Clean Controller object (and launch __destroy method of controller)
		unset($oController);
	}
}