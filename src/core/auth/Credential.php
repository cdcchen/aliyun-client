<?php
namespace cdcchen\aliyun\core\auth;


/**
 * Class Credential
 * @package cdcchen\aliyun\core\auth
 */
class Credential
{
    /**
     * Datetime format
     */
    const DATETIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * @var string Access key id
     */
    private $_accessKeyId;
    /**
     * @var string Access key secret
     */
    private $_accessSecret;

    private $refreshDate;
    private $expiredDate;
    private $securityToken;

    /**
     * Credential constructor.
     * @param string $accessKeyId
     * @param string $accessSecret
     */
    function __construct($accessKeyId, $accessSecret)
    {
        $this->setAccessKeyId($accessKeyId);
        $this->setAccessSecret($accessSecret);

        $this->refreshDate = date(self::DATETIME_FORMAT);
    }

    /**
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->_accessKeyId;
    }

    /**
     * @param string $accessKeyId
     */
    public function setAccessKeyId($accessKeyId)
    {
        $this->_accessKeyId = $accessKeyId;
    }

    /**
     * @return string
     */
    public function getAccessSecret()
    {
        return $this->_accessSecret;
    }

    /**
     * @param string $accessSecret
     */
    public function setAccessSecret($accessSecret)
    {
        $this->_accessSecret = $accessSecret;
    }


    public function isExpired()
    {
        if ($this->expiredDate == null) {
            return false;
        }

        if (strtotime($this->expiredDate) > date(self::DATETIME_FORMAT)) {
            return false;
        }

        return true;
    }

    public function getRefreshDate()
    {
        return $this->refreshDate;
    }

    public function getExpiredDate()
    {
        return $this->expiredDate;
    }

    public function setExpiredDate($expiredHours)
    {
        if ($expiredHours > 0) {
            $this->expiredDate = date(self::DATETIME_FORMAT, strtotime('+' . $expiredHours . ' hour'));
        }

        return $this;
    }

}