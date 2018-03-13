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
use Pabana\Routing\Router;

/**
 * Application class
 *
 * Enter point to Pabana
 */
class Application
{

    /**
     * @var     string Contains the path of the config directory
     * @since   1.0
     */
    protected $configDir;

    /**
     * Constructor
     *
     * @since   1.0
     * @param   string $configDir The directory of pabana config files.
     * @param   string $configFile Name of config file (by default "app.php").
     */
    public function __construct($configDir, $configFile = 'app.php')
    {
        $this->configDir = $configDir;
        $applicationConfigPath = $configDir . '/' . $configFile;
        if (!file_exists($applicationConfigPath)) {
            throw new \Exception('Application configuration file "' . $applicationConfigPath . '" doesn\' exist.');
        }
        // Register constant
        Configuration::registerConstant();
        // Store default settings for Pabana
        Configuration::base();
        // Load user config for Pabana
        Configuration::load($applicationConfigPath);
    }

    /**
     * Load bootstrap
     *
     * By default this will load \App\Bootstrap class.
     *
     * @since   1.0
     * @return  bool True is file is loaded else false.
     */
    private function bootstrap()
    {
        if (Configuration::read('bootstrap.enable') === true) {
            $applicationNamespace = Configuration::read('application.namespace');
            $bootstrapNamespace = $applicationNamespace . '\Bootstrap';
            if (!class_exists($bootstrapNamespace)) {
                throw new \Exception('Bootstrap "' . $bootstrapNamespace . '" doesn\' exist.');
                return false;
            }
            $bootstrap = new $bootstrapNamespace();
            if (!method_exists($bootstrap, 'initialize')) {
                $errorMessage = 'initialize() method isn\'t available in Bootstrap "' . $bootstrapNamespace . '".<br />';
                $errorMessage .= 'Your bootstrap may not extends \Pabana\Core\Bootstrap.';
                throw new \Exception($errorMessage);
                return false;
            }
            $bootstrap->initialize();
            return true;
        }
        return false;
    }

    /**
     * Load user defined databases
     *
     * By default this will load `config/databases.php`.
     *
     * @since   1.1
     * @return  bool True is file is loaded else false.
     */
    private function databases()
    {
        if (Configuration::read('database.config.enable') === true) {
            $databaseConfigPath = $this->configDir . DS . Configuration::read('database.config.file');
            if (file_exists($databaseConfigPath)) {
                require $databaseConfigPath;
                return true;
            }
        }
        return false;
    }

    /**
     * Load controller
     *
     * Load controller and action defined during routage and then destroy it.
     *
     * @since   1.0
     * @return  string|bool Return body content or false if controller can't be loaded.
     */
    private function controller()
    {
        $controllerName = Router::getController();
        $actionName = Router::getAction();
        $controllerNamespace = Configuration::read('mvc.controller.namespace');
        $controllerNamespace = $controllerNamespace . '\\' . $controllerName;
        if (!class_exists($controllerNamespace)) {
            throw new \Exception('Controller "' . $controllerNamespace . '" doesn\' exist.');
            return false;
        }
        $controller = new $controllerNamespace();
        if (!method_exists($controllerNamespace, 'render')) {
            $errorMessage = 'render() method isn\'t available in Controller "' . $controllerNamespace . '".<br />';
            $errorMessage .= 'Your controller may not extends \Pabana\Mvc\Controller.';
            throw new \Exception($errorMessage);
            return false;
        }
        $bodyContent = $controller->render($actionName);
        return $bodyContent;
    }

    /**
     * Load user defined routes
     *
     * By default this will load `config/routes.php`.
     *
     * @since   1.0
     * @return  bool True is file is loaded else false.
     */
    private function routes()
    {
        if (Configuration::read('routing.config.enable') === true) {
            $routingConfigPath = $this->configDir . DS . Configuration::read('routing.config.file');
            if (file_exists($routingConfigPath)) {
                require $routingConfigPath;
                return true;
            } else {
                throw new \Exception('Routing configuration file "' . $routingConfigPath . '" doesn\' exist.');
            }
        }
        return false;
    }
    
    /**
     * Run Pabana Framework
     *
     * Start by call user defined routes, then resolve routage
     * After start bootstrap and launch mvc by calling controller
     *
     * @since   1.0
     * @return  void
     */
    public function run()
    {
        // Load routing user config file
        $this->routes();
        // Launch routage
        Router::resolve();
        // Load databases user config file
        $this->databases();
        // Load Bootstrap
        $this->bootstrap();
        // Launch Controller defined by routage
        $bodyContent = $this->controller();
        // Show body content return by Controller
        echo $bodyContent;
    }
}
