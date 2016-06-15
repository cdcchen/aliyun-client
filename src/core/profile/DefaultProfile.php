<?php
namespace cdcchen\aliyun\core\profile;


use cdcchen\aliyun\core\auth\Credential;
use cdcchen\aliyun\core\auth\ShaHmac1Signer;
use cdcchen\aliyun\core\auth\ShaHmac256Signer;
use cdcchen\aliyun\core\auth\SignerInterface;

/**
 * Class DefaultProfile
 * @package cdcchen\aliyun\core\profile
 */
class DefaultProfile implements ClientProfileInterface
{
    /**
     * Json format
     */
    const FORMAT_JSON = 'json';
    /**
     * Xml format
     */
    const FORMAT_XML = 'xml';

    /**
     * @var ClientProfileInterface
     */
    private static $_profile;
    /**
     * @var Credential
     */
    private static $_credential;
    /**
     * @var string
     */
    private static $_format = self::FORMAT_JSON;
    /**
     * @var SignerInterface
     */
    private static $_signer;
    /**
     * @var string
     */
    private static $_signatureMethod = SignerInterface::HMAC_SHA1;

    /**
     * @param string $accessKeyId
     * @param string  $accessSecret
     * @return static
     */
    public static function getProfile($accessKeyId, $accessSecret)
    {
        self::$_credential = new Credential($accessKeyId, $accessSecret);
        self::$_profile = new static();
        return self::$_profile;
    }

    /**
     * @return ShaHmac1Signer|ShaHmac256Signer
     * @throws \ErrorException
     */
    public function getSigner()
    {
        if (self::$_signer === null) {
            if ($this->getSignatureMethod() == SignerInterface::HMAC_SHA1) {
                self::$_signer = new ShaHmac1Signer();
            } elseif ($this->getSignatureMethod() == SignerInterface::HMAC_SHA256) {
                self::$_signer = new ShaHmac256Signer();
            } else {
                throw new \ErrorException('Signature method is invalid.');
            }
        }

        return self::$_signer;
    }

    /**
     * @return Credential
     */
    public function getCredential()
    {
        return self::$_credential;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setFormat($value)
    {
        self::$_format = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return self::$_format;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSignatureMethod($value)
    {
        self::$_signatureMethod = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureMethod()
    {
        return self::$_signatureMethod;
    }
}