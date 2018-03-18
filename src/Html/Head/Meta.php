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
     * @since   1.0
     */
    private static $httpEquivList = array();

    /**
     * @var     array List of defined default meta
     * @since   1.0
     */
    private static $metaList = array();

    /**
     * toString
     *
     * Activate the render method
     *
     * @since   1.0
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
     * @since   1.0
     * @return  $this
     */
    public function clean()
    {
        self::$httpEquivList = array();
        self::$metaList = array();
    }

    /**
     * Render
     *
     * Return HTML code for initialize all meta in meta list
     *
     * @since   1.0
     * @return  string Html code to initialize meta
     */
    public function render()
    {
        $htmlContent = '';
        foreach (self::$httpEquivList as $httpEquivName => $httpEquivValue) {
            $htmlContent .= '<meta http-equiv="' . $httpEquivName . '" content="' . $httpEquivValue . '">' . PHP_EOL;
        }
        foreach (self::$metaList as $metaName => $metaValue) {
            $htmlContent .= '<meta name="' . $metaName . '" content="' . $metaValue . '">' . PHP_EOL;
        }
        return $htmlContent;
    }

    /**
     * Set a default meta
     *
     * @since   1.0
     * @param   string $metaName Meta tag name.
     * @param   string $metaValue Meta tag value.
     * @return  $this
     */
    public function set($metaName, $metaValue)
    {
        self::$metaList[$metaName] = $metaValue;
        return $this;
    }

    /**
     * Set meta tag 'application-name'
     *
     * @since   1.0
     * @param   string $applicationName Application name.
     * @return  $this
     */
    public function setApplicationName($applicationName)
    {
        return $this->set('application-name', $applicationName);
    }

    /**
     * Set meta tag 'author'
     *
     * @since   1.0
     * @param   string $author Author name.
     * @return  $this
     */
    public function setAuthor($author)
    {
        return $this->set('author', $author);
    }

    /**
     * Set meta tag 'default-style'
     *
     * @since   1.0
     * @param   string $defaultStyle Default style.
     * @return  $this
     */
    public function setDefaultStyle($defaultStyle)
    {
        return $this->setHttpEquiv('default-style', $defaultStyle);
    }

    /**
     * Set meta tag 'description'
     *
     * @since   1.0
     * @param   string $description Description.
     * @return  $this
     */
    public function setDescription($description)
    {
        return $this->set('description', $description);
    }

    /**
     * Set meta tag 'generator'
     *
     * @since   1.0
     * @param   string $generator Generator.
     * @return  $this
     */
    public function setGenerator($generator)
    {
        return $this->set('generator', $generator);
    }

    /**
     * Set a http-equiv meta
     *
     * @since   1.0
     * @param   string $httpEquivName Http-equiv name.
     * @param   string $httpEquivValue Http-equiv value.
     * @return  $this
     */
    public function setHttpEquiv($httpEquivName, $httpEquivValue)
    {
        self::$httpEquivList[$httpEquivName] = $httpEquivValue;
        return $this;
    }

    /**
     * Set meta tag 'keywords'
     *
     * @since   1.0
     * @param   string $keyword Keywords.
     * @return  $this
     */
    public function setKeyword($keyword)
    {
        return $this->set('keywords', $keyword);
    }

    /**
     * Set meta tag 'refresh'
     *
     * @since   1.0
     * @param   string $refresh Refresh.
     * @return  $this
     */
    public function setRefresh($refresh)
    {
        return $this->setHttpEquiv('refresh', $refresh);
    }

    /**
     * Set meta tag 'X-UA-Compatible'
     *
     * @since   1.0
     * @param   string $uaCompatible X-UA-Compatible.
     * @return  $this
     */
    public function setUaCompatible($uaCompatible)
    {
        return $this->setHttpEquiv('X-UA-Compatible', $uaCompatible);
    }

    /**
     * Set meta tag 'viewport'
     *
     * @since   1.0
     * @param   string $viewport Viewport.
     * @return  $this
     */
    public function setViewport($viewport)
    {
        return $this->set('viewport', $viewport);
    }
}
