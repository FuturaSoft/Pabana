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
namespace Pabana\Html;

/**
 * Doctype class
 *
 * Define doctype use
 */
class Doctype
{
    /**
     * @var     string Doctype version (by default HTML5).
     * @since   1.0
     */
    private static $doctype = 'HTML5';

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0
     * @return  string Html code for Doctype
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Clean
     *
     * Reinitialize Doctype value to HTML5
     *
     * @since   1.0
     * @return  string HTML5
     */
    public function clean()
    {
        return self::$doctype = 'HTML5';
    }

    /**
     * Get doctype
     *
     * Get current defined doctype
     *
     * @since   1.0
     * @return  string Current defined doctype
     */
    public function get()
    {
        return self::$doctype;
    }

    /**
     * Render
     *
     * Return HTML code for Doctype
     *
     * @since   1.0
     * @return  string Html code for Doctype
     */
    public function render()
    {
        if (self::$doctype == 'HTML5') {
            return '<!DOCTYPE html>' . PHP_EOL;
        } elseif (self::$doctype == 'XHTML11') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">' . PHP_EOL;
        } elseif (self::$doctype == 'XHTML1_STRICT') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . PHP_EOL;
        } elseif (self::$doctype == 'XHTML1_TRANSITIONAL') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . PHP_EOL;
        } elseif (self::$doctype == 'XHTML1_FRAMESET') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">' . PHP_EOL;
        } elseif (self::$doctype == 'HTML4_STRICT') {
            return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' . PHP_EOL;
        } elseif (self::$doctype == 'HTML4_LOOSE') {
            return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' . PHP_EOL;
        } elseif (self::$doctype == 'HTML4_FRAMESET') {
            return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">' . PHP_EOL;
        }
    }

    /**
     * Set doctype
     *
     * Set doctype
     *
     * @since   1.0
     * @param   string $doctype Doctype
     * @return  $this
     */
    public function set($doctype)
    {
        self::$doctype = $doctype;
        return $this;
    }
}
