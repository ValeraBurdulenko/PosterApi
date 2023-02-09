<?php

namespace src;

class PosterApi
{
    private static $example = null;

    public static function init($config = [])
    {
        self::$example = new PosterApiCore($config);
    }

    public static function reformierung()
    {
        return self::$example;
    }


    public static function auth()
    {
        return self::reformierung()->auth();
    }

    public static function menu()
    {
        return self::reformierung()->menu();
    }

    public static function sendRequest($url, $type = 'get', $params = '', $json = false)
    {
        return self::reformierung()->sendRequest($url, $type, $params, $json);
    }
}