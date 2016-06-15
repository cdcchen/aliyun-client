<?php
/**
 * Created by PhpStorm.
 * User: chendong
 * Date: 16/6/14
 * Time: 14:46
 */

namespace cdcchen\aliyun\core\base;


class Object
{
    public static function className()
    {
        return get_called_class();
    }

    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }

}