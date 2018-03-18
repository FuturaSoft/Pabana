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
 * @since         1.1
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Type;

/**
 * Manipulate string
 */
class StringType
{
    /**
     * @var     string String manipulate by this class.
     * @since   1.1
     */
    private $stringVar;
    
    /**
     * Constructor
     *
     * Initialize class with a String
     *
     * @since   1.1
     * @param   string $stringVar String who will be transform in StringType (by default empty String)
     * @return  void
     */
    public function __construct($stringVar = '')
    {
        $this->stringVar = $stringVar;
    }

    /**
     * Return string value
     *
     * @since   1.1
     * @return  string Value of String manipulated by this class.
     */
    public function __toString()
    {
        return $this->stringVar;
    }

    /**
     * Return charactère at a position.
     *
     * @since   1.1
     * @param   int $position Position of charactère
     * @return  char|bool Char at a position or false if no char at this position.
     */
    public function charAt($position)
    {
        if (isset($this->stringVar[$position])) {
            return $this->stringVar[$position];
        }
        return false;
    }

    /**
     * Return class basename from a namespace.
     *
     * @since   1.1
     * @return  string Class basename.
     */
    public function classBasename()
    {
        return basename(str_replace('\\', '/', $this->stringVar));
    }

    /**
     * Concatenates the specified mixed value to the end of this string.
     *
     * @since   1.1
     * @param   mixed $mixedVar The mixed value that is concatenated to the end of this String.
     * @return  void
     */
    public function concat($mixedVar)
    {
        $this->stringVar .= $mixedVar;
    }

    /**
     * Returns a new string resulting from replacing all occurrences of oldString in this string with newString.
     *
     * @since   1.1
     * @param   string $oldString The old string.
     * @param   string $newString The new string.
     * @return  void
     */
    public function replace($oldString, $newString)
    {
        $this->stringVar = str_replace($oldString, $newString, $this->stringVar);
    }
}
