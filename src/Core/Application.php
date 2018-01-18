<?php
namespace Pabana\Core;

use Pabana\Core\Configuration;
use Pabana\Network\Http\Request;
use Pabana\Routing\Router;

class Application {

	/**
     * @var string Contains the path of the config directory
     */
    protected $configDir;

    /**
     * Constructor
     *
     * @param string $configDir The directory of pabana config files.
     */
	public function __construct($sConfigDir, $sConfigFile = 'pabana.php')
	{
		$this->configDir = $sConfigDir;
		$sPabanaConfigPath = $sConfigDir . '/' . $sConfigFile;
		// Store default settings for Pabana
		Configuration::load($sPabanaConfigPath);
		// Initialize request object
		new Request();
    }

    /**
     * Load bootstrap
     *
     * By default this will load \App\Bootstrap class.
     *
     * @return void
     */
    private function bootstrap()
    {
    	$sAppNamespace = Configuration::read('application.namespace');
        new $sAppNamespace\Bootstrap;
    }

    /**
     * Load controller
     *
     * Load controller and action defined during routage and then destroy it.
     *
     * @return void
     */
    private function controller() {
    	$sAppNamespace = Configuration::read('application.namespace');
		new $sAppNamespace . '\Controller\\' . Router::$controller();
		$oController->init();
		if(method_exists($oController, 'initialize')) {
			$oController->initialize();
		}
		$sAction = Router::$action;
		$oController->$sAction();
		// Clean Controller object (and launch __destroy method of controller)
		unset($oController);
	}

    /**
     * Load user defined routes
     *
     * By default this will load `config/routes.php`.
     *
     * @return void
     */
    private function routes()
    {
        if (Configuration::read('routing.config.enable') === true) {
            require $this->configDir . '/' . Configuration::read('routing.config.file');
        }
    }
	
	public function run() {
		// Load bootstrap
		$this->bootstrap();
		// Initialize router value
		Router::initialize();
		// Load routing config file
		$this->routes();
		// Launch routage
		Router::route();
		// Launch controller defined by routage
		$this->controller();
	}
}