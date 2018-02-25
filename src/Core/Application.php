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
        $configPath = $configDir . '/' . $configFile;
        // Register constant
        Configuration::registerConstant();
        // Store default settings for Pabana
        Configuration::base();
        // Load user config for Pabana
        Configuration::load($configPath);
    }

    /**
     * Load bootstrap
     *
     * By default this will load \App\Bootstrap class.
     *
     * @since   1.0
     * @return  void
     */
    private function bootstrap()
    {
        $applicationNamespace = Configuration::read('application.namespace');
        $bootstrapNamespace = $applicationNamespace . '\Bootstrap';
        $bootstrap = new $bootstrapNamespace();
        $bootstrap->initialize();
    }

    /**
     * Load controller
     *
     * Load controller and action defined during routage and then destroy it.
     *
     * @since   1.0
     * @return  void
     */
    private function controller()
    {
        $controllerName = Router::getController();
        $actionName = Router::getAction();
        $controllerNamespace = Configuration::read('mvc.controller.namespace');
        $controllerNamespace = $controllerNamespace . '\\' . $controllerName;
        $controller = new $controllerNamespace();
        $bodyContent = $controller->render($actionName);
        return $bodyContent;
    }

    /**
     * Load user defined routes
     *
     * By default this will load `config/routes.php`.
     *
     * @since   1.0
     * @return  void
     */
    private function routes()
    {
        if (Configuration::read('routing.config.enable') === true) {
            require $this->configDir . '/' . Configuration::read('routing.config.file');
        }
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
        // Load Bootstrap
        $this->bootstrap();
        // Launch Controller defined by routage
        $bodyContent = $this->controller();
        // Show body content return by Controller
        echo $bodyContent;
    }
}
