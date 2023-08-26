<?php
namespace Modules\Admin\Helpers;
use ReflectionClass;

class Constants
{
    const USER_TYPE_SUPER_ADMIN = 1;
    const USER_TYPE_NORMAL = 2;
    const USER_TYPE_INVEST = 4;
    const USER_TYPE_UPDATE_PROJECT = 8;
    const USER_TYPE_OWNER_PROJECT = 16;

    public static function getClassConstants() {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstants();
    }

    public static function getTypeUsers(){
        return [
            self::USER_TYPE_SUPER_ADMIN => "Super Admin",
            self::USER_TYPE_NORMAL => "Thành viên đăng ký",
            self::USER_TYPE_INVEST => "Nhà đầu tư",
            self::USER_TYPE_UPDATE_PROJECT => "Cập nhật tiến độ",
            self::USER_TYPE_OWNER_PROJECT => "Chủ dự án",
        ];
    }
}
