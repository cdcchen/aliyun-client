<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/15
 * Time: 19:22
 */

namespace cdcchen\aliyun\core\base;

/**
 * Class ServerException
 * @package cdcchen\aliyun\dm\core\base
 */
class ServerException extends ClientException
{
    /**
     * ServerException constructor.
     * @param string $errorMessage
     * @param string $errorCode
     * @param int $httpStatus
     * @param int $code
     */
    public function __construct($errorMessage, $errorCode, $httpStatus = 0, $code = 0)
    {
        parent::__construct($errorMessage, $errorCode, $httpStatus, $code);

        $this->setErrorType('Server');
    }
}