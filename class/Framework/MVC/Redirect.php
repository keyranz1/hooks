<?php

namespace Framework\MVC;


class Redirect
{
    static function to($location){
        if(strpos($location,'http') === false){
            header('Location:'.BASE_URL.$location);
        } else {
            header('Location:'.$location);
        }
    }

    static function trigger($num){
        switch($num){
            case 404:
                Route::routeMVC("home/error404");
                break;

            case 500:
                Route::routeMVC("home/error500");
                break;

            default:
                die($num.' page not found');
                break;
        }

    }
}