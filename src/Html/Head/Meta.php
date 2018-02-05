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
namespace Pabana\Html\Head;

class Meta
{
    private static $_arsHttpEquiv = array();
    private static $_arsMeta = array();

    public function __toString()
    {
        return $this->render();
    }

    public function clean()
    {
        self::$_arsHttpEquiv = array();
        self::$_arsMeta = array();
    }

    public function render()
    {
        $sHtml = '';
        foreach (self::$_arsHttpEquiv as $sHttpEquivName => $sHttpEquivValue) {
            $sHtml .= '<meta http-equiv="' . $sHttpEquivName . '" content="' . $sHttpEquivValue . '">' . PHP_EOL;
        }
        foreach (self::$_arsMeta as $sMetaName => $sMetaValue) {
            $sHtml .= '<meta name="' . $sMetaName . '" content="' . $sMetaValue . '">' . PHP_EOL;
        }
        return $sHtml;
    }

    public function set($sMetaName, $sMetaValue)
    {
        self::$_arsMeta[$sMetaName] = $sMetaValue;
        return $this;
    }

    public function setApplicationName($sApplicationName)
    {
        return $this->set('application-name', $sApplicationName);
    }

    public function setAuthor($sAuthor)
    {
        return $this->set('author', $sAuthor);
    }

    public function setDefaultStyle($sDefaultStyle)
    {
        return $this->setHttpEquiv('default-style', $sDefaultStyle);
    }

    public function setDescription($sDescription)
    {
        return $this->set('description', $sDescription);
    }

    public function setGenerator($sGenerator)
    {
        return $this->set('generator', $sGenerator);
    }

    public function setHttpEquiv($sHttpEquivName, $sHttpEquivValue)
    {
        self::$_arsHttpEquiv[$sHttpEquivName] = $sHttpEquivValue;
        return $this;
    }

    public function setKeyword($sKeyword)
    {
        return $this->set('keywords', $sKeyword);
    }

    public function setRefresh($sRefresh)
    {
        return $this->setHttpEquiv('refresh', $sRefresh);
    }

    public function setUaCompatible($sUaCompatible)
    {
        return $this->setHttpEquiv('X-UA-Compatible', $sUaCompatible);
    }

    public function setViewport($sViewport)
    {
        return $this->set('viewport', $sViewport);
    }
}
