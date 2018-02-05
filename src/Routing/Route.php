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
namespace Pabana\Routing;

class Route
{
    private $_sRoute = null;
    private $_sController = 'index';
    private $_sAction = 'index';
    private $_arsParamList = null;

    public function __construct($sRoute, $arsOption = array())
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

    public function getRoute()
    {
        return $this->_sRoute;
    }

    public function getController()
    {
        return $this->_sController;
    }

    public function getAction()
    {
        return $this->_sAction;
    }

    public function getParamList()
    {
        return $this->_arsParamList;
    }
}
