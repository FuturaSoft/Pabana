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
namespace Pabana\Mvc;

use Pabana\Core\Configuration;
use Pabana\Html\Html;
use Pabana\Mvc\Layout;
use Pabana\Mvc\Model;
use Pabana\Mvc\View;
use Pabana\Network\Http\Request;
use Pabana\Routing\Router;

class Mvc
{
    protected $Html;
    protected $Layout;
    protected $Model;
    protected $Request;
    protected $View;

    final public function __construct()
    {
        // Load Html\Html helper to $Html var
        $this->Html = new Html();
        // Load Mvc\Layout to $Layout var
        $this->Layout = new Layout();
        // Load Mvc\Model to $Model var
        $this->Model = new Model();
        // Load Network\Http\Request helper to $Request var
        $this->Request = new Request();
        // Load Mvc\View to $View var
        $this->View = new View();
        // Set default view name
        $this->View->setName(Router::getAction());
    }
}
