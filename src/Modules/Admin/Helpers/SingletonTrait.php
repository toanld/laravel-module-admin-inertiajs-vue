<?php
/**
 * Created by Lê Đình Toản.
 * User: dinhtoan1905@gmail.com
 * Date: 11/22/2019
 * Time: 3:24 PM
 */
namespace Modules\Admin\Helpers;
trait SingletonTrait
{
    /**
     * @var self[]
     */
    private static $instances = [];

    /**
     * Returns instance, if instance does not exist then creates new one and returns it
     *
     * @return $this
     */
    public static function getInstance()
    {
        $self = get_called_class();
        if (!isset(self::$instances[$self])) {
            self::$instances[$self] = new $self;
        }
        return self::$instances[$self];
    }

    /**
     * @return bool true if has instance, otherwise false
     */
    protected static function hasInstance()
    {
        $self = get_called_class();
        return isset(self::$instances[$self]);
    }
}
