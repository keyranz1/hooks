<?php


if(!function_exists("autoloader")){

    function autoloader($class) {

        if (strpos($class,'Razr') === false)
        {
            $file = BASE_DIR . DIRECTORY_SEPARATOR.  "class" . DIRECTORY_SEPARATOR . $class . ".php";
        }

        else
        {
            $file = BASE_DIR . DIRECTORY_SEPARATOR.  "class" .DIRECTORY_SEPARATOR. "Razr". DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $class . ".php";
        }


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

