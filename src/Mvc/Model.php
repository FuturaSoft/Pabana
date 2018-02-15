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
use Pabana\Database\ConnectionCollection;

/**
 * Model class
 *
 * Manage Model
 */
class Model
{
    /**
     * @var     \Pabana\Database\Connection Object Connection (default connection).
     * @since   1.0.0
     */
    public $Connection;

    /**
     * Initialize model
     *
     * If default Connection is defined, call it in $Connection var
     *
     * @since   1.0.0
     * @return  void
     */
    public function __construct()
    {
        if (ConnectionCollection::existsDefault() === true) {
            $this->Connection = ConnectionCollection::getDefault();
        }
    }

    /**
     * Get model
     *
     * Call a model class
     *
     * @since   1.0.0
     * @param   string $sModel Model class name
     * @return  object Model defined in $sModel
     */
    public function get($sModel)
    {
        $sAppNamespace = Configuration::read('application.namespace');
        $sModelNamespace = $sAppNamespace . '\Model\\' . ucFirst($sModel);
        return new $sModelNamespace();
    }
}
