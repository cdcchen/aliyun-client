<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/15
 * Time: 19:26
 */

namespace cdcchen\aliyun\core\base;


use Exception;

/**
 * Class ClientException
 * @package cdcchen\aliyun\dm\core\base
 */
class ClientException extends Exception
{
    /**
     * @var int
     */
    protected $httpStatus;
    /**
     * @var string
     */
    protected $errorCode;
    /**
     * @var string
     */
    protected $errorMessage;
    /**
     * @var string
     */
    protected $requestId;
    /**
     * @var string
     */
    protected $hostId;
    /**
     * @var string
     */
    protected $errorType;

    /**
     * ClientException constructor.
     * @param string $errorMessage
     * @param string $errorCode
     * @param int $httpStatus
     * @param int $code
     */
    public function __construct($errorMessage, $errorCode, $httpStatus = 0, $code = 0)
    {
        parent::__construct($errorMessage, $code);

        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->httpStatus = $httpStatus;
        $this->setErrorType('Client');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setRequestId($value)
    {
        $this->requestId = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setHostId($value)
    {
        $this->hostId = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setErrorType($value)
    {
        $this->errorType = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getHostId()
    {
        return $this->hostId;
    }

    /**
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }
}