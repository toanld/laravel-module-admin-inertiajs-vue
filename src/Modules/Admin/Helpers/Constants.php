<?php
namespace Modules\Admin\Helpers;
use ReflectionClass;

class Constants
{
    const ADMIN_TYPE_SUPER = 1;
    public static function getClassConstants() {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstants();
    }
}
