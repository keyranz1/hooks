<?php


namespace Controllers;


use hooks\MVC\Controller;
use hooks\Services\Image;
use hooks\Storage\File;

class ImageController extends Controller
{

    public function render($file){

        $file = "assets/temp-img/" . $file;

        if(File::exists( $file)){

            Image::deliver($file);

        }

        return null;

    }

}