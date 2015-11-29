<?php


namespace Controllers;


use Framework\MVC\Controller;
use Framework\Services\Image;
use Framework\Storage\File;

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