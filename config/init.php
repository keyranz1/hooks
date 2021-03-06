<?php

define("SERVER_INIT" , microtime(true));


if(!isset($_SESSION)){
    session_start();
}

if(
    (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == "https") ||
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on" ) ||
    (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443 )
){
    define("REQUEST_SCHEME", "https");
} else {
    define("REQUEST_SCHEME", "http");
}

date_default_timezone_set ( "Europe/Copenhagen" );

if(!defined("BASE_DIR")){
    define("BASE_DIR", dirname(__DIR__));
    define("BASE_URL_RELATIVE", explode($_SERVER['DOCUMENT_ROOT'],BASE_DIR)[1].'/');
    define("BASE_URL", REQUEST_SCHEME.'://'.$_SERVER['HTTP_HOST'].''.BASE_URL_RELATIVE);
}

//Grabbing constants
require_once BASE_DIR . '/config/constants.php';
require_once BASE_DIR . '/config/autoloader.php';
require_once BASE_DIR.'/config/routes.php';
require_once BASE_DIR.'/config/helperFunctions.php';



