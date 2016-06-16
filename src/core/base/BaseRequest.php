<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/7
 * Time: 22:47
 */

namespace cdcchen\aliyun\core\base;


/**
 * Class BaseClient
 * @package cdcchen\aliyundm
 */
abstract class BaseRequest extends Object
{
    /**
     * @var string
     */
    public $action;
    /**
     * @var array
     */
    private $_params = [];

    /**
     * BaseClient constructor.
     */
    public function __construct()
    {
        $this->init();
        $this->setDefaultParams();
    }

    /**
     * init
     */
    protected function init()
    {
    }

    /**
     * @return string Api version
     */
    abstract function getVersion();

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
     * before excute
     */
    public function beforeExecute()
    {
        $this->prepare();
        $this->setParam('Action', $this->action);
        $this->checkRequireParams();
    }

    /**
     * Set default params
     */
    protected function setDefaultParams()
    {
    }

    /**
     * @return array
     */
    abstract protected function getRequireParams();

    /**
     * prepare for execute
     */
    protected function prepare()
    {
    }

    /**
     * check require params
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
}