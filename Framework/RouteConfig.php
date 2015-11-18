<?php

namespace Framework;

class RouteConfig{

    public static $routes = [];

    /*
     * matches $uri to it's variables
     * eg: /usa/texas/ && {country}/{state}
     * and puts $country = usa etc.
     */

    public static function route($uri)
    {

        $uri = self::cleanQuery($uri);
        if (array_key_exists($uri, self::$routes)) {
            Route::get(self::$routes[$uri]);
            return true;
        }


        $_uri = explode('/',$uri);



        foreach (self::$routes as $route => $method) {


            $VariablesToPass = [];

            $_route = explode('/',$route);


            if (count($_route) === count($_uri)) {


                $___go = true;

                for ($i = 0; $i < count($_route); $i++) {

                    if (isset($_route[$i][0]) && $_route[$i][0] === "{") {
                        $var = substr($_route[$i], 1, strlen($_route[$i]) - 2);
                        $VariablesToPass[$var] = $_uri[$i];
                    } else {
                        if ($_route[$i] !== $_uri[$i]) {
                            $___go = false;
                            break;      //Break this
                            continue;  //Continue main
                        }
                    }

                }


                if($___go){
                    Route::get($method, $VariablesToPass);
                    return true;
                }




            }
        }


        Redirect::trigger(404);
        return false;

    }

    public static function cleanQuery($uri){
        $uris = explode("?",$uri);
        $uri = $uris[0];
        return rtrim($uri,"/");
    }



}