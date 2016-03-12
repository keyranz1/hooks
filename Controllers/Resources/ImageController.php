<?php


namespace Controllers\Resources;


use hooks\Media\Image;
use hooks\MVC\Controller;
use hooks\Storage\FileSystem;

class ImageResourceController extends Controller
{

    public function render($file){

        $file = "assets/temp-img/" . $file;

        if(FileSystem::exists( $file) && FileSystem::isImage( $file )){

            Image::deliver($file);

        }

        return null;

    }

}