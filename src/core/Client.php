<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/7
 * Time: 22:47
 */

namespace cdcchen\aliyun\core;


use cdcchen\aliyun\core\base\BaseRequest;
use cdcchen\aliyun\core\base\ClientException;
use cdcchen\aliyun\core\base\Object;
use cdcchen\aliyun\core\base\ServerException;
use cdcchen\aliyun\core\profile\ClientProfileInterface;
use cdcchen\net\curl\Client as CUrlClient;
use cdcchen\net\curl\HttpResponse;

/**
 * Class BaseCUrlClient
 * @package cdcchen\aliyun\core
 */
class Client extends Object
{
    /**
     * @var ClientProfileInterface
     */
    private $_profile;

    /**
     * @var string
     */
    private $_accessKeyId;
    /**
     * @var string
     */
    private $_accessKeySecret;
    /**
     * @var array
     */
    private $_params = [];

    /**
     * @var bool
     */
    private $_https = true;
    /**
     * @var string
     */
    private $_restUrl = 'dm.aliyuncs.com';

    /**
     * @var array
     */
    private $_filters = [];

    /**
     * BaseCUrlClient constructor.
     * @param ClientProfileInterface $profile
     */
    public function __construct(ClientProfileInterface $profile)
    {
        $this->_profile = $profile;
        $credential = $profile->getCredential();
        $this->setKeyIdSecret($credential->getAccessKeyId(), $credential->getAccessSecret())
             ->setDefaultParams()
             ->init();
    }

    /**
     * init
     */
    protected function init()
    {
    }

    /**
     * @return ClientProfileInterface
     */
    public function getProfile()
    {
        return $this->_profile;
    }

    /**
     * @param $keyId
     * @param $secret
     * @return $this
     */
    public function setKeyIdSecret($keyId, $secret)
    {
        $this->_accessKeyId = $keyId;
        $this->_accessKeySecret = $secret;
        $this->setParam('AccessKeyId', $keyId);
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setEnableSSL($value = true)
    {
        $this->_https = (bool)$value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRestUrl($value)
    {
        $parts = explode('://', $value, 2);
        if (isset($parts[1])) {
            $this->_restUrl = $parts[1];
            $this->setEnableSSL(strtolower($parts[0]) === 'https');
        } else {
            $this->_restUrl = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getRestUrl()
    {
        return ($this->_https ? 'https://' : 'http://') . $this->_restUrl;
    }

    /**
     * @param string $value json or xml
     * @return $this
     */
    public function setFormat($value)
    {
        return $this->setParam('Format', $value);
    }

    /**
     * @param string
     * @return $this
     */
    public function setVersion($value)
    {
        return $this->setParam('Version', $value);
    }

    /**
     * @param string
     * @return $this
     */
    public function setSignatureVersion($value)
    {
        return $this->setParam('SignatureVersion', $value);
    }

    /**
     * @return $this
     */
    public function setTimestamp()
    {
        return $this->setParam('Timestamp', gmdate('Y-m-d\TH:i:s\Z'));
    }

    /**
     * @return $this
     */
    public function setSignatureNonce()
    {
        return $this->setParam('SignatureNonce', uniqid());
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;
        return $this;
    }

    /**
     * @param null|string $name
     * @return array|bool|mixed
     */
    public function getParam($name)
    {
        return isset($this->_params[$name]) ? $this->_params[$name] : false;
    }

    /**
     * @return array|bool|mixed
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        foreach ($params as $name => $value) {
            $this->setParam($name, $value);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function setDefaultParams()
    {
        $params = [
            'Format' => $this->getProfile()->getFormat(),
            'SignatureMethod' => $this->getProfile()->getSigner()->getSignatureMethod(),
            'SignatureVersion' => $this->getProfile()->getSigner()->getSignatureVersion(),
        ];

        return $this->setParams($params);
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setFilter(callable $callback)
    {
        $this->_filters[] = $callback;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * @param HttpResponse $response
     * @return $this
     */
    private function applyFilters(HttpResponse &$response)
    {
        foreach ($this->_filters as $filter) {
            $response = call_user_func($filter, $response);
        }

        return $this;
    }

    /**
     * @param BaseRequest $request
     * @return Response
     * @throws \cdcchen\net\curl\RequestException
     */
    public function execute(BaseRequest $request)
    {
        if (!$this->beforeExecute($request)) {
            return false;
        }

        $request->beforeExecute();
        $this->mergeRequestParams($request);

        $this->prepare();
        /* @var HttpResponse $response */
        $response = CUrlClient::post($this->getRestUrl(), $this->_params)->send();
        $this->applyFilters($response);
        return $this->afterExecute($response);
    }

    /**
     * @param BaseRequest $request
     * @return bool
     */
    protected function beforeExecute(BaseRequest $request)
    {
        return true;
    }

    /**
     * @param HttpResponse $response
     * @return Response
     * @throws ClientException | ServerException
     */
    protected function afterExecute(HttpResponse $response)
    {
        $statusCode = $response->getStatus();
        if ($response->isSuccess()) {
            return new Response($statusCode, $response->getData());
        } else {
            $data = $response->getData();
            static::throwException($data, $statusCode);
        }
    }

    /**
     * @param $data
     * @param $httpStatus
     * @throws ClientException | ServerException
     */
    private static function throwException($data, $httpStatus)
    {
        if ($httpStatus < 500) {
            $throw = new ClientException($data['Message'], $data['Code'], $httpStatus);
        } else {
            $throw = new ServerException($data['Message'], $data['Code'], $httpStatus);
        }

        $throw->setHostId($data['HostId'])->setRequestId($data['RequestId']);
        throw $throw;
    }

    /**
     * @param BaseRequest $request
     * @return $this
     */
    private function mergeRequestParams(BaseRequest $request)
    {
        $this->setVersion($request->getVersion());
        $this->_params = array_merge($this->_params, $request->getParams());
        return $this;
    }

    /**
     * prepare for execute
     */
    private function prepare()
    {
        $this->setTimestamp()
             ->setSignatureNonce()
             ->checkAccessKeyIdSecret()
             ->checkRequireParams();

        $this->setParam('Signature', $this->generateSign());
    }

    /**
     * Check keyId and appSecret
     * @return  $this
     */
    private function checkAccessKeyIdSecret()
    {
        if (empty($this->_accessKeyId) || empty($this->_accessKeySecret)) {
            throw new \InvalidArgumentException('Appkey and secret is required.');
        }

        return $this;
    }

    /**
     * Check require params
     */
    private function checkRequireParams()
    {
        $requireParams = $this->getRequireParams();

        foreach ($requireParams as $param) {
            if (!isset($this->_params[$param])) {
                throw new \InvalidArgumentException("$param is required.");
            }
        }
    }

    /**
     * @return array
     */
    protected static function getRequireParams()
    {
        return ['Version', 'AccessKeyId', 'SignatureMethod', 'Timestamp', 'SignatureVersion', 'SignatureNonce'];
    }


    /**
     * @return string
     */
    private function generateSign()
    {
        ksort($this->_params);

        $params = [];
        foreach ($this->_params as $name => $value) {
            $params[] = rawurlencode($name) . '=' . rawurlencode($value);
        }

        $stringToSign = 'POST&%2F&' . rawurlencode(join('&', $params));
        return $this->_profile->getSigner()->buildSignature($stringToSign, $this->_accessKeySecret . '&');
    }

}