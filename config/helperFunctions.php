<?php

use hooks\Media\Image;
use hooks\MVC\Redirect;
use hooks\MVC\Route;
use hooks\MVC\View;
use hooks\Storage\DB;
use Models\App;
use hooks\Services\Tracker;


function image(string $path) : Image{
    return new Image($path);
}

/*
function getImageSrc($path, $width = null,  $height = null, $aspect = true){
    $image = new Image($path);
    return $image->height($height)->width($width)->aspect($aspect)->src();
}
*/

function app() : App{
    return new App();
}

function db() : DB{
    return new DB();
}

function view($view = null, $vars = []){
    return new View($view, $vars);
}

function component(string $component){
    $component = "ViewComponents\\" . ucfirst($component) . "ViewComponent";
    $viewComponent = new $component;
    call_user_func_array(array($viewComponent, "invoke"), []);
}

function route() : Route{
    return new Route();
}

function redirect() : Redirect{
    return new Redirect();
}

function tracker() : Tracker{
    return new Tracker();
}