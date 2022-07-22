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
namespace Pabana\Network\Http;

use Pabana\Network\Http\Request\File;
use Pabana\Network\Http\Request\Input;
use Pabana\Network\Http\Request\Query;

/**
 * Request class
 *
 * Parse Http request
 */
class Request
{
    /**
     * @var    Array List of Request headers
     * @since   1.1
     */
    private $headerList = array();

    /**
     * @var     \Pabana\Network\Http\Request\File    Object to file managment
     * @since   1.2
     */
    public $file;

    /**
     * @var     \Pabana\Network\Http\Request\Input    Object to input managment
     * @since   1.2
     */
    public $input;

    /**
     * @var     \Pabana\Network\Http\Request\Query    Object to query managment
     * @since   1.2
     */
    public $query;

    /**
     * Constructor
     *
     * @since   1.1
     * @version 1.2
     */
    public function __construct()
    {
        $this->headerList = $this->getHeaderList();
        $this->file = new File();
        $this->input = new Input();
        $this->query = new Query();
    }

    /**
     * Check accept charset
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @param   string $charset Test charset.
     * @return  bool True if test is ok else false.
     */
    public function acceptCharset($charset)
    {
        return $this->isAcceptCharset($charset);
    }

    /**
     * Check accept encoding
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @param   string $encoding Test encoding.
     * @return  bool True if test is ok else false.
     */
    public function acceptEncoding($encoding)
    {
        return $this->isAcceptEncoding($encoding);
    }

    /**
     * Check accept language
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @param   string $language Test language.
     * @return  bool True if test is ok else false.
     */
    public function acceptLanguage($language)
    {
        return $this->isAcceptLanguage($language);
    }

    /**
     * Check accept mimetype
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @param   string $mimetype Test mimetype.
     * @return  bool True if test is ok else false.
     */
    public function acceptMimetype($mimetype)
    {
        return $this->isAcceptMimetype($mimetype);
    }

    /**
     * Get client IP
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @return  string Return client IP.
     */
    public function clientIp()
    {
        return $this->getClientIp();
    }

    /**
     * Get domain
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @param   string $tldLength Length of Top Level Domain. (by default 1)
     * @return  string Return domain.
     */
    public function domain($tldLength = 1)
    {
        return $this->getDomain($tldLength);
    }

    /**
     * Get Authorization method
     *
     * @since   1.1
     * @return  string Return Authorization method.
     */
    public function getAuthorizationMethod()
    {
        if ($this->hasHeader('Authorization')) {
            $authorization = $this->getHeader('Authorization');
            return trim(strstr($authorization, ' ', true));
        }
        return false;
    }

    /**
     * Get Authorization value
     *
     * @since   1.1
     * @return  string Return Authorization value.
     */
    public function getAuthorizationValue()
    {
        if ($this->hasHeader('Authorization')) {
            $authorization = $this->getHeader('Authorization');
            return strstr($authorization, ' ');
        }
        return false;
    }

    /**
     * Get client IP
     *
     * @since   1.1
     * @return  string Return client IP.
     */
    public function getClientIp()
    {
        if ($this->hasHeader('X-Forwarded-For')) {
            $ip = preg_replace('/(?:,.*)/', '', $this->getHeader('X-Forwarded-For'));
        } elseif ($this->hasHeader('Client-Ip')) {
            $ip = $this->getHeader('Client-Ip');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return trim($ip);
    }

    /**
     * Get domain
     *
     * @since   1.1
     * @param   string $tldLength Length of Top Level Domain. (by default 1)
     * @return  string Return domain.
     */
    public function getDomain($tldLength = 1)
    {
        $segmentList = explode('.', $this->getHost());
        $domain = array_slice($segmentList, -1 * ($tldLength + 1));
        return implode('.', $domain);
    }

    /**
     * Get header value
     *
     * @since   1.1
     * @param   string $headerName Name of header
     * @return  mixed Return value of header if exist or false if not exist.
     */
    public function getHeader($headerName)
    {
        if ($this->hasHeader($headerName)) {
            return $this->headerList[$headerName];
        }
        return false;
    }

    /**
     * Get header list
     *
     * @since   1.1
     * @return  array Return array of header for current request.
     */
    public function getHeaderList()
    {
        if (!function_exists('apache_request_headers')) {
            $headers = [];
        } else {
            $headers = apache_request_headers();
        }
        $copy_server = array(
            'CONTENT_TYPE'   => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5'    => 'Content-Md5'
        );
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (isset($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }
        if (!isset($headers['Authorization'])) {
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }
        return $headers;
    }

    /**
     * Get host
     *
     * @since   1.1
     * @return  string Return host.
     */
    public function getHost()
    {
        return $this->getHeader('host');
    }

    /**
     * Get request method
     *
     * @since   1.1
     * @return  string Return request method (GET, POST, ...).
     */
    public function getMethod()
    {
        if ($this->hasHeader('X-Http-Method-Override')) {
            return $this->getHeader('X-Http-Method-Override');
        }
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get request port
     *
     * @since   1.1
     * @return  integer Return request port.
     */
    public function getPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * Get referer
     *
     * @since   1.1
     * @return  string Return referer.
     */
    public function getReferer()
    {
        return $this->getHeader('Referer');
    }

    /**
     * Get request scheme
     *
     * @since   1.1
     * @return  string Return request scheme (http or https).
     */
    public function getScheme()
    {
        if (isset($_SERVER['HTTPS']) || empty($_SERVER['HTTPS'])) {
            return 'https';
        } else {
            return 'http';
        }
    }

    /**
     * Get subdomain
     *
     * @since   1.1
     * @param   string $tldLength Length of Top Level Domain. (by default 1)
     * @return  string Return subdomain.
     */
    public function getSubdomain($tldLength = 1)
    {
        $segmentList = explode('.', $this->getHost());
        return array_slice($segmentList, 0, -1 * ($tldLength + 1));
    }

    /**
     * Get url
     *
     * @since   1.1
     * @return  string Return request url.
     */
    public function getUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get user agent
     *
     * @since   1.1
     * @return  string Return user agent.
     */
    public function getUserAgent()
    {
        return $this->getHeader('User-Agent');
    }

    /**
     * Check if header exist
     *
     * @since   1.1
     * @param   string $headerName Name of header
     * @return  mixed Return true if exist or false if not exist.
     */
    public function hasHeader($headerName)
    {
        return isset($this->headerList[$headerName]);
    }

    /**
     * Get host
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @return  string Return host.
     */
    public function host()
    {
        return $this->getHost();
    }

    /**
     * Check method, ajax, json and xml
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @param   string $sTest Type of test (get, put, patch, post, delete, head, options, ajax, json, xml)
     * @return  bool Return true if test is ok or return false.
     */
    public function is($sTest)
    {
        $arsMethod = array('get', 'put', 'patch', 'post', 'delete', 'head', 'options');
        if (in_array($sTest, $arsMethod)) {
            if (strtolower($this->getMethod()) == $sTest) {
                return true;
            } else {
                return false;
            }
        } elseif ($sTest == 'ajax') {
            if (strtolower($this->getHeader('X-Requested-With')) == 'xmlhttprequest') {
                return true;
            } else {
                return false;
            }
        } elseif ($sTest == 'json') {
            if ($this->isAcceptMimetype('application/json') === true) {
                return true;
            } else {
                return false;
            }
        } elseif ($sTest == 'xml') {
            if ($this->isAcceptMimetype('application/xml') === true || $this->isAcceptMimetype('text/xml') === true) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Check accept field
     *
     * @since   1.1
     * @param   string $acceptHeader Accept header.
     * @param   string $test Accept test.
     * @return  bool True if test is ok else false.
     */
    private function isAccept($acceptHeader, $test)
    {
        $acceptList = $this->parseAccept($acceptHeader);
        if (empty($test)) {
            return $acceptList;
        } else {
            foreach ($acceptList as $acceptType) {
                if (in_array($test, $acceptType)) {
                    return true;
                }
                if (in_array('*/*', $acceptType)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Check accept charset
     *
     * @since   1.1
     * @param   string $charset Test charset.
     * @return  bool True if test is ok else false.
     */
    public function isAcceptCharset($charset)
    {
        if ($this->hasHeader('Accept-Charset')) {
            return $this->isAccept($this->getHeader('Accept-Charset'), $charset);
        }
        return false;
    }

    /**
     * Check accept encoding
     *
     * @since   1.1
     * @param   string $encoding Test encoding.
     * @return  bool True if test is ok else false.
     */
    public function isAcceptEncoding($encoding)
    {
        if ($this->hasHeader('Accept-Encoding')) {
            return $this->isAccept($this->getHeader('Accept-Encoding'), $encoding);
        }
        return false;
    }

    /**
     * Check accept language
     *
     * @since   1.1
     * @param   string $language Test language.
     * @return  bool True if test is ok else false.
     */
    public function isAcceptLanguage($language)
    {
        if ($this->hasHeader('Accept-Language')) {
            return $this->isAccept($this->getHeader('Accept-Language'), $language);
        }
        return false;
    }

    /**
     * Check accept mimetype
     *
     * @since   1.1
     * @param   string $mimetype Test mimetype.
     * @return  bool True if test is ok else false.
     */
    public function isAcceptMimetype($mimetype)
    {
        if ($this->hasHeader('Accept')) {
            return $this->isAccept($this->getHeader('Accept'), $mimetype);
        }
        return false;
    }

    /**
     * Check ajax
     *
     * @since   1.1
     * @return  bool Return true if test is ok or return false.
     */
    public function isAjax()
    {
        if (strtolower($this->getHeader('X-Requested-With')) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    /**
     * Check method
     *
     * @since   1.1
     * @param   string $methodName Method name (GET, POST, ...).
     * @return  bool Return true if test is ok or return false.
     */
    public function isMethod($methodName)
    {
        if ($this->getMethod() == strtoupper($methodName)) {
            return true;
        }
        return false;
    }

    /**
     * Get request method
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @return  string Return request method (GET, POST, ...).
     */
    public function method()
    {
        return $this->getMethod();
    }

    /**
     * Parse accept type line
     *
     * @since   1.0
     * @param   string $sHeaderLine Accept header line.
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
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @return  integer Return request port.
     */
    public function port()
    {
        return $this->getPort();
    }

    /**
     * Get request scheme
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @return  string Return request scheme (http or https).
     */
    public function scheme()
    {
        return $this->getScheme();
    }

    /**
     * Get subdomain
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @param   string $tldLength Length of Top Level Domain. (by default 1)
     * @return  string Return subdomain.
     */
    public function subdomain($tldLength = 1)
    {
        return $this->getSubdomain($tldLength);
    }

    /**
     * Get request url
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @return  string Return request url.
     */
    public function url()
    {
        return $this->getUrl();
    }

    /**
     * Get user agent
     *
     * @since   1.0
     * @deprecated deprecated since version 1.1
     * @return  string Return user agent.
     */
    public function userAgent()
    {
        return $this->getUserAgent();
    }
}
