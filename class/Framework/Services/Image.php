<?php

namespace Framework\Services;

use Framework\Storage\File;
use Framework\Storage\Session;

class Image
{
    private $file,                  //string
            $height,                //int
            $width,                 //int
            $preserveAspectRatio = true;  //bool

    private $originalHeight, $originalWidth, $imageType, $resizeRatio;
    private $thumb;


    public function __construct($file)
    {

        $this->file = File::getRealPath($file);

    }

    public function src(){
        $this->prepare();

        if(!File::exists( "assets/temp-img/" . $this->tempFileName())){
            $this->prepareFromImageType();
            $this->crop();
        }

        //URL
        return BASE_URL . "/image/" . $this->tempFileName();

    }

    public static function deliver($imageFile){

        $imageFile = File::getRealPath($imageFile);

        if (File::exists($imageFile)) {

            $imageInfo = getimagesize($imageFile);

            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    header("Content-Type: image/jpg",1);
                    break;
                case IMAGETYPE_GIF:
                    header("Content-Type: image/gif");
                    break;
                case IMAGETYPE_PNG:
                    header("Content-Type: image/png",1);
                    break;
                default:
                    break;
            }

            // Set the content-length header
            header('Content-Length: ' . filesize($imageFile));

            // Write the image bytes to the client
            readfile($imageFile);

        }
    }

    private function prepare(){

        $image_properties = getimagesize($this->file);
        $this->originalWidth = $image_properties[0];
        $this->originalHeight = $image_properties[1];
        $this->resizeRatio = $this->originalWidth / $this->originalHeight;
        $this->imageType = $image_properties["mime"];

        if($this->height === null)
            $this->height = $this->originalHeight;
        if($this->width === null)
            $this->width = $this->originalWidth;


    }

    private function prepareFromImageType(){

        switch($this->imageType){
            case "image/jpeg":
                $this->thumb = imagecreatefromjpeg($this->file); //jpeg file
                break;
            case "image/gif":
                $this->thumb = imagecreatefromgif($this->file); //gif file
                break;
            case "image/png":
                $this->thumb = imagecreatefrompng($this->file); //png file
                break;
            default:
                die("Only jpeg, png and gif is supported");
                break;
        }

    }

    private function tempFileName(){
        switch($this->imageType){
            case  "image/jpeg":
                return  md5($this->file) . "." . $this->width . "x" . $this->height . "." . $this->preserveAspectRatio . ".jpg";

            case  "image/png":
                return  md5($this->file) . "." . $this->width . "x" . $this->height. "." . $this->preserveAspectRatio . ".png";

            case  "image/gif":
                return md5($this->file) . "." . $this->width . "x" . $this->height . "." . $this->preserveAspectRatio . ".gif";

            default:
                return null;
        }
    }

    private function crop(){

        if($this->preserveAspectRatio){
            $thumbnail = $this->cropPreservingAspectRation();
        } else {
            $thumbnail = $this->cropWithoutPreservingAspectRation();
        }

        $this->saveTempImage($thumbnail);

    }

    private function saveTempImage($thumbnail){

        $fileName =  "assets/temp-img/" . $this->tempFileName();

        switch($this->imageType){

            case  "image/jpeg":
                imagejpeg($thumbnail, $fileName);
                break;

            case  "image/png":
                imagepng($thumbnail, $fileName);
                break;

            case  "image/gif":
                imagegif($thumbnail, $fileName);
                break;

            default:
                die("Invalid Image Type");
                break;

        }
    }

    private function getCropSourcePoints(){

        $originalAR = $this->originalWidth / $this->originalHeight;
        $requestedAR = $this->width / $this->height;


        if($originalAR > $requestedAR){
            $cropHeight = $this->originalHeight;
            $cropWidth = round($cropHeight * $requestedAR);

        } else {
            $cropWidth = $this->originalWidth;
            $cropHeight = round($cropWidth / $requestedAR);
        }

        $sourceX = ($this->originalWidth - $cropWidth) / 2;
        $sourceY = ($this->originalHeight - $cropHeight) / 2;

        return (object) [
            "x" => $sourceX,
            "y"=>  $sourceY,
            "width" => $cropWidth,
            "height" => $cropHeight
        ];
    }

    private function cropPreservingAspectRation(){

        $crop = $this->getCropSourcePoints();


        $thumbnail = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($thumbnail, $this->thumb, 0, 0, $crop->x, $crop->y, $this->width, $this->height, $crop->width, $crop->height);

        return $thumbnail;
    }

    private function cropWithoutPreservingAspectRation(){

        $thumbnail = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($thumbnail, $this->thumb, 0, 0, 0, 0, $this->width, $this->height, $this->originalWidth, $this->originalHeight);

        return $thumbnail;

    }



    public function height($height){
        $this->height = $height;
        return $this;
    }

    public function width($width){
        $this->width = $width;
        return $this;
    }

    public function aspect($preserve){

        $this->preserveAspectRatio = (bool) $preserve;
        return $this;
    }



}