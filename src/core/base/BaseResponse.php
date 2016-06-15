<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/14
 * Time: 11:50
 */

namespace cdcchen\aliyun\core\base;


/**
 * Class BaseResponse
 * @package cdcchen\aliyundm
 */
abstract class BaseResponse extends Object
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    private $_data;

    /**
     * Error constructor.
     * @param int $status
     * @param array $data
     */
    public function __construct($status, $data = [])
    {
        $this->statusCode = (int)$status;
        $this->setData($data);
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    /**
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->get('RequestId');
    }

    /**
     * @return bool
     */
    public function isOK()
    {
        return $this->statusCode == 200;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

}