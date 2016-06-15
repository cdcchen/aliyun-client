<?php
namespace cdcchen\aliyun\core\profile;


/**
 * Interface ClientProfileInterface
 * @package cdcchen\aliyun\core\profile
 */
interface ClientProfileInterface
{
    /**
     * @return \cdcchen\aliyun\core\auth\SignerInterface
     */
    public function getSigner();

    /**
     * @return string
     */
    public function getFormat();

    /**
     * @return \cdcchen\aliyun\core\auth\Credential
     */
    public function getCredential();

    /**
     * @return string
     */
    public function getSignatureMethod();
}