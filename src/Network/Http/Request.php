<?php
namespace Pabana\Network\Http;

class Request
{
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

    public function acceptCharset($sType = null)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT_CHARSET'], $sType);
    }

    public function acceptEncoding($sType = null)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT_ENCODING'], $sType);
    }

    public function acceptLanguage($sType = null)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT_LANGUAGE'], $sType);
    }

    public function acceptMimetype($sType = null)
    {
        return $this->accept($_SERVER['HTTP_ACCEPT'], $sType);
    }

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

    public function domain($nTldLength = 1)
    {
        $arsSegments = explode('.', $this->host());
        $arsDomain = array_slice($arsSegments, -1 * ($nTldLength + 1));
        return implode('.', $arsDomain);
    }

    public function host()
    {
        return $_SERVER['HTTP_HOST'];
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

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

    public function port()
    {
        return $_SERVER['SERVER_PORT'];
    }

    public function scheme()
    {
        if (isset($_SERVER['HTTPS']) || is_empty($_SERVER['HTTPS'])) {
            return 'https';
        } else {
            return 'http';
        }
    }

    public function subdomain($nTldLength = 1)
    {
        $arsSegments = explode('.', $this->host());
        return array_slice($arsSegments, 0, -1 * ($nTldLength + 1));
    }

    public function url()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

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
