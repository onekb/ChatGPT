<?php

namespace Onekb\ChatGpt;

class Di
{
    protected static $container = [];

    public static function set($key, $value)
    {
        self::$container[$key] = $value;
    }

    /**
     * @param $key
     * @param array $options 传递给构造函数的参数
     *
     * @return mixed
     */
    public static function get($key, $options = [])
    {
        if (! self::has($key)) {
            $class = new $key(...$options);
            self::set($key, $class);
        }

        return self::$container[$key];
    }

    public static function has($key)
    {
        return isset(self::$container[$key]);
    }

    public static function all()
    {
        return self::$container;
    }
}