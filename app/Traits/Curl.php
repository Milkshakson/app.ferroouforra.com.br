<?php

namespace App\Traits;

use Exception;

trait Curl
{

    protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';

    protected $_url;

    protected $_followlocation;

    protected $_timeout;

    protected $_maxRedirects;

    protected $_cookieFileLocation = './cookie.txt';

    protected $_post;

    protected $_postFields;

    protected $_referer = "http://www.google.com";

    protected $_session;

    protected $_webpage;

    protected $_includeHeader;

    protected $_noBody;

    protected $_status;

    protected $_binaryTransfer;

    protected $_requestType;

    protected $_header;

    public $authentication = 0;

    public $auth_name = '';

    public $auth_pass = '';

    protected $ssl_cert = '';

    protected $ssl_password = '';

    public function useAuth($use)
    {
        $this->authentication = 0;
        if ($use == true) {
            $this->authentication = 1;
        }

    }

    public function setName($name)
    {
        $this->auth_name = $name;
    }

    public function setPass($pass)
    {
        $this->auth_pass = $pass;
    }

    public function initialize($config = array())
    {
        $url = '';
        $followlocation = true;
        $timeOut = 30;
        $maxRedirecs = 4;
        $binaryTransfer = false;
        $includeHeader = false;
        $noBody = false;
        extract($config);
        $this->_url = $url;
        $this->_followlocation = $followlocation;
        $this->_timeout = $timeOut;
        $this->_maxRedirects = $maxRedirecs;
        $this->_noBody = $noBody;
        $this->_includeHeader = $includeHeader;
        $this->_binaryTransfer = $binaryTransfer;

        $this->_cookieFileLocation = dirname(__FILE__) . '/cookie.txt';
    }
    public function setReferer($referer)
    {
        $this->_referer = $referer;
    }

    public function setCookiFileLocation($path)
    {
        $this->_cookieFileLocation = $path;
    }

    public function setPost($postFields)
    {
        $this->_post = true;
        $this->_postFields = $postFields;
    }

    public function setRequestType($type, $data = null)
    {
        $this->_requestType = $type;
        $this->_postFields = $data;
    }

    public function setHeader($data, $merge = false)
    {
        if (is_null($this->_header) || $merge == false) {
            $header = $data;
        } else {
            $header = array_merge($this->_header, $data);
        }
        $this->_header = array_unique($header);
    }

    public function setUserAgent($userAgent)
    {
        $this->_useragent = $userAgent;
    }

    public function createCurl($url = null)
    {
        try {
            if (!is_null($url)) {
                $this->_url = $url;
            }
            $s = curl_init();
            curl_setopt($s, CURLOPT_URL, $this->_url);
            if ($this->_header) {
                curl_setopt($s, CURLOPT_HTTPHEADER, $this->_header);
            } else {
                curl_setopt($s, CURLOPT_HTTPHEADER, array(
                    'Expect:',
                ));
            }
            curl_setopt($s, CURLOPT_TIMEOUT, $this->_timeout);
            curl_setopt($s, CURLOPT_MAXREDIRS, $this->_maxRedirects);
            curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($s, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
            curl_setopt($s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
            curl_setopt($s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
            if ($this->authentication == 1) {
                curl_setopt($s, CURLOPT_USERPWD, $this->auth_name . ':' . $this->auth_pass);
            }
            if ($this->_post) {
                curl_setopt($s, CURLOPT_POST, true);
                curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
            }

            if ($this->_includeHeader) {
                curl_setopt($s, CURLOPT_HEADER, true);
            }

            if ($this->_noBody) {
                curl_setopt($s, CURLOPT_NOBODY, true);
            }
            if (!key_exists('HTTPS', $_SERVER)) {
                curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($s, CURLOPT_SSL_VERIFYHOST, false);
            }

            if ($this->_requestType) {
                curl_setopt($s, CURLOPT_CUSTOMREQUEST, $this->_requestType);

                switch ($this->_requestType) {
                    case 'POST':
                        curl_setopt($s, CURLOPT_POST, true);
                        curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
                        break;
                    case 'GET':
                        curl_setopt($s, CURLOPT_HTTPGET, true);
                        break;
                    case 'DELETE':
                    case 'PUT':
                        curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
                        break;
                    default:
                        break;
                }
            }
            curl_setopt($s, CURLOPT_USERAGENT, $this->_useragent);
            curl_setopt($s, CURLOPT_REFERER, $this->_referer);
            curl_setopt($s, CURLINFO_HEADER_OUT, true);
            $this->_webpage = curl_exec($s);
            $this->_status = curl_getinfo($s, CURLINFO_HTTP_CODE);
            if (curl_errno($s)) {
                throw new Exception(curl_error($s), 1);
            }

            curl_close($s);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getHttpStatus()
    {
        return $this->_status;
    }

    public function __tostring()
    {
        return $this->_webpage;
    }

    /**
     * Get the value of _header
     */
    public function getHeader()
    {
        return $this->_header;
    }
}