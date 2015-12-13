<?php
use App\App;
use Framework\MVC\Redirect;
use Framework\MVC\Route;
use Framework\MVC\View;
use Framework\Services\Image;
use Framework\Services\ServerTracker;
use Framework\Storage\DB;
use ViewComponents\TestViewComponent;

function image($path){
        return new Image($path);
    }

    function getImageSrc($path, $width = null,  $height = null, $aspect = true){
        $image = new Image($path);
        return $image->height($height)->width($width)->aspect($aspect)->src();
    }

    function app(){
        return new App();
    }

    function db(){
        return new DB();
    }

    function view($view = null, $vars = []){
        return new View($view, $vars);
    }

    function component($component){
        $component = "ViewComponents\\" . ucfirst($component) . "ViewComponent";
        $viewComponent = new $component;
        call_user_func_array(array($viewComponent, "invoke"), []);

    }

    function route(){
        return new Route();
    }

    function redirect(){
        return new Redirect();
    }

    function tracker(){
        return new ServerTracker();
    }