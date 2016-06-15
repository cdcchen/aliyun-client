<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/7
 * Time: 20:09
 */

namespace cdcchen\aliyun\dm;

use cdcchen\aliyun\core\base\BaseRequest;
use cdcchen\aliyun\core\helpers\ClientHelper;


/**
 * Class SingleSendMailRequest
 * @package cdcchen\aliyundm
 */
class SingleSendMailRequest extends BaseRequest
{
    use VersionTrait;
    
    const ADDRESS_TYPE_RAND = 0;
    const ADDRESS_TYPE_SEND = 1;

    /**
     * @var string
     */
    public $action = 'SingleSendMail';

    /**
     * @param string $value
     * @return $this
     */
    public function setAccountName($value)
    {
        return $this->setParam('AccountName', $value);
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setReplyToAddress($value = true)
    {
        return $this->setParam('ReplyToAddress', ClientHelper::convertBoolToString($value));
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAddressType($value)
    {
        return $this->setParam('AddressType', $value);
    }

    /**
     * @param string|array $value
     * @return $this
     */
    public function setToAddress($value)
    {
        if (is_array($value)) {
            $value = join(',', $value);
        }
        return $this->setParam('ToAddress', $value);
    }

    /**
     * @param array|string $value
     * @return $this
     */
    public function setFromAlias($value)
    {
        return $this->setParam('FromAlias', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setSubject($value)
    {
        return $this->setParam('Subject', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setHtmlBody($value)
    {
        return $this->setParam('HtmlBody', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTextBody($value)
    {
        return $this->setParam('TextBody', $value);
    }

    /**
     * @return array
     */
    protected function getRequireParams()
    {
        return ['Action', 'AccountName', 'ReplyToAddress', 'AddressType', 'ToAddress'];
    }

}