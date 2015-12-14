<?php

namespace hooks\MVC;


class Redirect
{
    static function to(string $location){
        if(strpos($location,'http') === false){
            header('Location:'.BASE_URL.$location);
        } else {
            header('Location:'.$location);
        }
    }

    static function trigger(int $num){
        switch($num){
            case 404:
                route()->routeMVC("errors/error404");
                break;

            case 500:
                route()->routeMVC("errors/error500");
                break;

            default:
                route()->routeMVC("errors/error500");
                break;
        }

    }
}