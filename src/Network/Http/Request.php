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
namespace Pabana\Network\Http;

/**
 * Request class
 *
 * Parse Http request
 */
class Request
{
	/**
     * Check accept field
     *
     * @since   1.0.0
     * @param   string $sContent Accept header.
     * @param   string $sType Accept test.
     * @return  bool True if test is ok else false.
     */
    private function accept($sContent, $sType)
    {
        $arsAcceptMimetype = $this->parseAccept($sContent);
        if (empty($sType)) {
            return $arsAcceptMimetype;
        } else {
            foreach ($arsAcceptMimetype as $arsAcceptList) {
                if (in_array($sType, $arsAcceptList)) {
                    return true;
                }
                if (in_array('*/*', $arsAcceptList)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Check accept charset
     *
     * @since   1.0.0
     * @param   string $sCharset Test charset.
     * @return  bool True if test is ok else false.
     */
    public function acceptCharset($sCharset)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT_CHARSET'], $sCharset);
    }

    /**
     * Check accept encoding
     *
     * @since   1.0.0
     * @param   string $sEncoding Test encoding.
     * @return  bool True if test is ok else false.
     */
    public function acceptEncoding($sEncoding)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT_ENCODING'], $sEncoding);
    }

    /**
     * Check accept language
     *
     * @since   1.0.0
     * @param   string $sLanguage Test language.
     * @return  bool True if test is ok else false.
     */
    public function acceptLanguage($sLanguage)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT_LANGUAGE'], $sLanguage);
    }

    /**
     * Check accept mimetype
     *
     * @since   1.0.0
     * @param   string $sMimetype Test mimetype.
     * @return  bool True if test is ok else false.
     */
    public function acceptMimetype($sMimetype)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT'], $sMimetype);
    }

    /**
     * Get client IP
     *
     * @since   1.0.0
     * @return  string Return client IP.
     */
    public function clientIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $sIp = preg_replace('/(?:,.*)/', '', $_SERVER['HTTP_X_FORWARDED_FOR']);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $sIp = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $sIp = $_SERVER['REMOTE_ADDR'];
        }
        return trim($sIp);
    }

    /**
     * Get domain
     *
     * @since   1.0.0
     * @param  	string $nTldLength Length of Top Level Domain. (by default 1)
     * @return  string Return domain.
     */
    public function domain($nTldLength = 1)
    {
        $arsSegments = explode('.', $this->host());
        $arsDomain = array_slice($arsSegments, -1 * ($nTldLength + 1));
        return implode('.', $arsDomain);
    }

    /**
     * Get host
     *
     * @since   1.0.0
     * @return  string Return host.
     */
    public function host()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Get request method
     *
     * @since   1.0.0
     * @return  string Return request method (GET, POST, ...).
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Parse accept type line
     *
     * @since   1.0.0
     * @param  	string $sHeaderLine Accept header line.
     * @return  array Return sort accept value.
     */
    private function parseAccept($sHeaderLine)
    {
        $arsAccept = [];
        $arsHeader = explode(',', $sHeaderLine);
        foreach (array_filter($arsHeader) as $sValue) {
            $nPrefValue = '1.0';
            $sValue = trim($sValue);
            $semiPos = strpos($sValue, ';');
            if ($semiPos !== false) {
                $arsParams = explode(';', $sValue);
                $sValue = trim($arsParams[0]);
                foreach ($arsParams as $arsParam) {
                    $nQPos = strpos($arsParam, 'q=');
                    if ($nQPos !== false) {
                        $nPrefValue = substr($arsParam, $nQPos + 2);
                    }
                }
            }
            if (!isset($arsAccept[$nPrefValue])) {
                $arsAccept[$nPrefValue] = [];
            }
            if ($nPrefValue) {
                $arsAccept[$nPrefValue][] = $sValue;
            }
        }
        krsort($arsAccept);
        return $arsAccept;
    }

    /**
     * Get request port
     *
     * @since   1.0.0
     * @return  integer Return request port.
     */
    public function port()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * Get request scheme
     *
     * @since   1.0.0
     * @return  string Return request scheme (http or https).
     */
    public function scheme()
    {
        if (isset($_SERVER['HTTPS']) || is_empty($_SERVER['HTTPS'])) {
            return 'https';
        } else {
            return 'http';
        }
    }

    /**
     * Get subdomain
     *
     * @since   1.0.0
     * @param  	string $nTldLength Length of Top Level Domain. (by default 1)
     * @return  string Return subdomain.
     */
    public function subdomain($nTldLength = 1)
    {
        $arsSegments = explode('.', $this->host());
        return array_slice($arsSegments, 0, -1 * ($nTldLength + 1));
    }

    /**
     * Get request url
     *
     * @since   1.0.0
     * @return  string Return request url.
     */
    public function url()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get user agent
     *
     * @since   1.0.0
     * @return  string Return user agent.
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * Check method, ajax, json and xml
     *
     * @since   1.0.0
     * @param  	string $sTest Type of test (get, put, patch, post, delete, head, options, ajax, json, xml)
     * @return  bool Return true if test is ok or return false.
     */
    public function is($sTest)
    {
        $arsMethod = array('get', 'put', 'patch', 'post', 'delete', 'head', 'options');
        if (in_array($sTest, $arsMethod)) {
            if (strtolower($this->method()) == $sTest) {
                return true;
            } else {
                return false;
            }
        } elseif ($sTest == 'ajax') {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                return true;
            } else {
                return false;
            }
        } elseif ($sTest == 'json') {
            if ($this->acceptMimetype('application/json') === true) {
                return true;
            } else {
                return false;
            }
        } elseif ($sTest == 'xml') {
            if ($this->acceptMimetype('application/xml') === true || $this->acceptMimetype('text/xml') === true) {
                return true;
            } else {
                return false;
            }
        }
    }
}