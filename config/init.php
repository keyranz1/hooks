<?php

define("SERVER_INIT" , microtime(true));


if(!isset($_SESSION)){
    session_start();
}

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('America/Chicago');
}

if(!defined("BASE_DIR")){
    define("BASE_DIR", dirname(__DIR__));
    define("BASE_URL_RELATIVE", explode($_SERVER['DOCUMENT_ROOT'],BASE_DIR)[1].'/');
    define("BASE_URL", $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].''.BASE_URL_RELATIVE);
}

//Grabbing constants
require_once BASE_DIR . '/config/constants.php';
require_once BASE_DIR . '/config/autoloader.php';
require_once BASE_DIR.'/config/routes.php';
require_once BASE_DIR.'/config/helperFunctions.php';
require_once BASE_DIR.'/config/class_alias.php';

