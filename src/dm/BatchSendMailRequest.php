<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/7
 * Time: 20:09
 */

namespace cdcchen\aliyun\dm;

use cdcchen\aliyun\core\base\BaseRequest;


/**
 * Class BatchSendMailRequest
 * @package cdcchen\aliyundm
 */
class BatchSendMailRequest extends BaseRequest
{
    use VersionTrait;
    
    const ADDRESS_TYPE_RAND = 0;
    const ADDRESS_TYPE_SEND = 1;

    /**
     * @var string
     */
    public $action = 'BatchSendMail';

    /**
     * @param string $value
     * @return $this
     */
    public function setAccountName($value)
    {
        return $this->setParam('AccountName', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTemplateName($value)
    {
        return $this->setParam('TemplateName', $value);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setReceiversName($value)
    {
        return $this->setParam('ReceiversName', $value);
    }

    /**
     * @param array|string $value
     * @return $this
     */
    public function setTagName($value)
    {
        return $this->setParam('TagName', $value);
    }

    /**
     * @return array
     */
    protected function getRequireParams()
    {
        return ['Action', 'AccountName', 'TemplateName', 'AddressType', 'ReceiversName'];
    }

}