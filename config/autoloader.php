<?php


if(!function_exists("autoloader")){

    function autoloader($class) {

        $file = BASE_DIR . DIRECTORY_SEPARATOR.  "class" . DIRECTORY_SEPARATOR . $class . ".php";


        $file = str_replace("/" , DIRECTORY_SEPARATOR, $file);
        $file = str_replace("\\" , DIRECTORY_SEPARATOR, $file);


        if(file_exists($file)){
            require_once $file;
        } else {
            die("Failed to include class " . $class . " as " . $file);
        }

    }
}


spl_autoload_register('autoloader');

