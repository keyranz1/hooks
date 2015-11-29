<?php
use App\App;
use Framework\MVC\View;
use Framework\Services\Image;
use Framework\Storage\DB;

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
