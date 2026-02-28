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
namespace Pabana\Routing;

/**
 * Route class
 *
 * Define a route
 */
class Route
{
    /**
     * @var    string Route
     * @since   1.0
     */
    private ?string $_sRoute = null;

    /**
     * @var    string Redirect controller
     * @since   1.0
     */
    private string $_sController = 'index';

    /**
     * @var    string Redirect action
     * @since   1.0
     */
    private string $_sAction = 'index';

    /**
     * @var    array|null Redirect param list
     * @since   1.0
     */
    private ?array $_arsParamList = null;

    /**
     * Create an object Route from parameters
     *
     * @since   1.0
     * @param   string $sRoute Route.
     * @param   array $arsOption Option (controller, action and param).
     */
    public function __construct(string $sRoute, array $arsOption = [])
    {
        $this->_sRoute = $sRoute;
        if (isset($arsOption['controller'])) {
            $this->_sController = $arsOption['controller'];
        }
        if (isset($arsOption['action'])) {
            $this->_sAction = $arsOption['action'];
        }
        if (isset($arsOption['param'])) {
            $this->_arsParamList = $arsOption['param'];
        }
    }

    /**
     * Get route defined
     *
     * @since   1.0
     * @return  string Route.
     */
    public function getRoute(): ?string
    {
        return $this->_sRoute;
    }

    /**
     * Get controller defined
     *
     * @since   1.0
     * @return  string Controller.
     */
    public function getController(): string
    {
        return $this->_sController;
    }

    /**
     * Get action defined
     *
     * @since   1.0
     * @return  string Action.
     */
    public function getAction(): string
    {
        return $this->_sAction;
    }

    /**
     * Get param list.
     *
     * @since   1.0
     * @return  array|null Param list.
     */
    public function getParamList(): ?array
    {
        return $this->_arsParamList;
    }
}
