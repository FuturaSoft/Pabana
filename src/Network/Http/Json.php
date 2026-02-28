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
 * @since         1.2
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Network\Http;

/**
 * Manipulate Json
 */
class Json
{
    /**
     * Send JSON response
     *
     * @since   1.2
     * @param   array $data Data to encode as JSON.
     * @param   int $code HTTP response code.
     * @return  bool Return true if success code (2xx) else false.
     */
    public static function send(array $data = [], int $code = 200): bool
    {
        http_response_code($code);
        header('Content-Type: application/json');
        if (!empty($data)) {
            echo json_encode($data, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
        } else {
            echo '{}';
        }
        return $code >= 200 && $code < 300;
    }
}
