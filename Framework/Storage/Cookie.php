<?php
namespace Framework\Storage;


class Cookie
{
    public static function isItemSet($item){
        return isset($_COOKIE[$item]);
    }

    public static function getItem($item){
        return (self::isItemSet($item)) ? $_COOKIE[$item] : null;
    }

    public static function setItem($item, $value, $time = 604800){ //plus a week
        setcookie($item,$value,time() + $time, "/");
    }

    public static function removeItem($item){
        unset($_COOKIE[$item]);
    }

    public static function flushItem($item){
        $value = (self::isItemSet($item)) ? $_COOKIE[$item] : null;
        unset($_COOKIE[$item]);
        return $value;
    }


}