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

/**
 * Meta class
 *
 * Add meta tag to Html
 */
class Meta
{
    /**
     * @var     array List of defined meta http-equiv
     * @since   1.0.0
     */
    private static $_arsHttpEquiv = array();

    /**
     * @var     array List of defined default meta
     * @since   1.0.0
     */
    private static $_arsMeta = array();

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0.0
     * @return  string Html code to initialize meta
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Clean
     *
     * Clean list of default meta and http-equiv meta
     *
     * @since   1.0.0
     * @return  $this
     */
    public function clean()
    {
        self::$_arsHttpEquiv = array();
        self::$_arsMeta = array();
    }

    /**
     * Render
     *
     * Return HTML code for initialize all meta in meta list
     *
     * @since   1.0.0
     * @return  string Html code to initialize meta
     */
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

    /**
     * Set a default meta
     *
     * @since   1.0.0
     * @param   string $sMetaName Meta tag name.
     * @param   string $sMetaValue Meta tag value.
     * @return  $this
     */
    public function set($sMetaName, $sMetaValue)
    {
        self::$_arsMeta[$sMetaName] = $sMetaValue;
        return $this;
    }

    /**
     * Set meta tag 'application-name'
     *
     * @since   1.0.0
     * @param   string $sApplicationName Application name.
     * @return  $this
     */
    public function setApplicationName($sApplicationName)
    {
        return $this->set('application-name', $sApplicationName);
    }

    /**
     * Set meta tag 'author'
     *
     * @since   1.0.0
     * @param   string $sAuthor Author name.
     * @return  $this
     */
    public function setAuthor($sAuthor)
    {
        return $this->set('author', $sAuthor);
    }

    /**
     * Set meta tag 'default-style'
     *
     * @since   1.0.0
     * @param   string $sDefaultStyle Default style.
     * @return  $this
     */
    public function setDefaultStyle($sDefaultStyle)
    {
        return $this->setHttpEquiv('default-style', $sDefaultStyle);
    }

    /**
     * Set meta tag 'description'
     *
     * @since   1.0.0
     * @param   string $sDescription Description.
     * @return  $this
     */
    public function setDescription($sDescription)
    {
        return $this->set('description', $sDescription);
    }

    /**
     * Set meta tag 'generator'
     *
     * @since   1.0.0
     * @param   string $sGenerator Generator.
     * @return  $this
     */
    public function setGenerator($sGenerator)
    {
        return $this->set('generator', $sGenerator);
    }

    /**
     * Set a http-equiv meta
     *
     * @since   1.0.0
     * @param   string $sHttpEquivName Http-equiv name.
     * @param   string $sHttpEquivValue Http-equiv value.
     * @return  $this
     */
    public function setHttpEquiv($sHttpEquivName, $sHttpEquivValue)
    {
        self::$_arsHttpEquiv[$sHttpEquivName] = $sHttpEquivValue;
        return $this;
    }

    /**
     * Set meta tag 'keywords'
     *
     * @since   1.0.0
     * @param   string $sKeyword Keywords.
     * @return  $this
     */
    public function setKeyword($sKeyword)
    {
        return $this->set('keywords', $sKeyword);
    }

    /**
     * Set meta tag 'refresh'
     *
     * @since   1.0.0
     * @param   string $sRefresh Refresh.
     * @return  $this
     */
    public function setRefresh($sRefresh)
    {
        return $this->setHttpEquiv('refresh', $sRefresh);
    }

    /**
     * Set meta tag 'X-UA-Compatible'
     *
     * @since   1.0.0
     * @param   string $sUaCompatible X-UA-Compatible.
     * @return  $this
     */
    public function setUaCompatible($sUaCompatible)
    {
        return $this->setHttpEquiv('X-UA-Compatible', $sUaCompatible);
    }

    /**
     * Set meta tag 'viewport'
     *
     * @since   1.0.0
     * @param   string $sViewport Viewport.
     * @return  $this
     */
    public function setViewport($sViewport)
    {
        return $this->set('viewport', $sViewport);
    }
}
