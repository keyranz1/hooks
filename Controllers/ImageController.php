<?php


namespace Controllers;


use hooks\Media\Image;
use hooks\MVC\Controller;
use hooks\Storage\FileSystem;

class ImageController extends Controller
{

    public function render($file){

        $file = "assets/temp-img/" . $file;

        if(FileSystem::exists( $file)){

            Image::deliver($file);

        }

        return null;

    }

}