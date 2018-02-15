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
use Pabana\Network\Request;

/**
 * Controller class
 *
 * Launch controller and call Layout and view if defined
 */
class Controller extends Mvc
{
    /**
     * Initialize controller
     *
     * Call Initialize of Layout is Layout autorender enable
     *
     * @since   1.0.0
     * @return  void
     */
    final public function init()
    {
        if ($this->Layout->getAutoRender()) {
            // Get Layout init
            $this->Layout->renderInit();
        }
    }

    /**
     * Destruct controller
     *
     * Call render of view and layout if autorender enable
     * Then show html return of controller
     *
     * @since   1.0.0
     * @return  void
     */
    final public function __destruct()
    {
        $sOutHtml = null;
        if ($this->View->getAutoRender()) {
            // Get view
            $sOutHtml = $this->View->render();
        }
        if ($this->Layout->getAutoRender()) {
            // Get layout and view
            $sOutHtml = $this->Layout->render();
        }
        // Send Html
        if (!empty($sOutHtml)) {
            echo $sOutHtml;
        }
    }
}