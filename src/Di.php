<?php

namespace Onekb\ChatGpt;

class Di
{
    protected static $container = [];

    public static function set($key, $value)
    {
        self::$container[$key] = $value;
    }

    public static function get($key)
    {
        if (! self::has($key)) {
            self::set($key, new $key());
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