<?php

namespace Framework\Storage;


class File
{
    public static function exists($file){
        return (file_exists($file));
    }

    public static function isDirectory($file){
        return is_dir($file);
    }

    public static function move($filePathFrom, $destinationDirectory){
        return move_uploaded_file ( $filePathFrom , $destinationDirectory );
    }

    public static function isFileUploaded(){
        if(isset($_FILES)){
            foreach($_FILES as $file){
                if($file["size"] > 0)
                    return true; //One is enough
            }

        }
        return false;
    }

    public static function uploadFiles($to = "assets/uploads"){

        if(!self::exists($to)){
            self::makeDirectory($to);
        }

        $filesUploaded = [];
        if(self::isFileUploaded()){
            foreach($_FILES as $file){
                $uploadPath = $to."/".time()."-".$file["name"];
                try{
                    self::move($file["tmp_name"],$uploadPath);
                    $filesUploaded[] = $uploadPath;
                    @chmod(BASE_DIR ."/" . $uploadPath, 0777);
                } catch (\Exception $e){
                    die("Error uploading file". $e->getMessage());
                }

            }
        }
        return $filesUploaded;
    }

    public static function makeDirectory($dir){
        try{
            mkdir ( $dir );
        } catch (\Exception $e){
            die("Error Making Directory file". $e->getMessage());
        }
    }

    public static function cropImage($file, $w = null, $h = null, $aspectRatio = true){

        $url = "img=/".$file;
        if($w != null)
            $url .= "&w=" .  $w;
        if($h != null)
            $url .= "&h=" .  $h;
        if($aspectRatio)
            $url .= "&a=" .  $aspectRatio;

        $final = BASE_URL."assets/services/image.php?". ($url);

        return ($final);
    }

}