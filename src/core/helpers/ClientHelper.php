<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/15
 * Time: 11:07
 */

namespace cdcchen\aliyun\core\helpers;


class ClientHelper
{
    public static function convertBoolToString($value)
    {
        return $value ? 'true' : 'false';
    }
}