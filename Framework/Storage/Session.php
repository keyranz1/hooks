<?php
namespace Framework\Storage;


class Session
{
    public static function isItemSet($item){
        return isset($_SESSION[$item]);
    }

    public static function getItem($item){
        return (self::isItemSet($item)) ? $_SESSION[$item] : null;
    }

    public static function setItem($item, $value){
        $_SESSION[$item] = $value;
    }

    public static function removeItem($item){
        unset($_SESSION[$item]);
    }

    public static function flushItem($item){
        $value = (self::isItemSet($item)) ? $_SESSION[$item] : null;
        unset($_SESSION[$item]);
        return $value;
    }

}