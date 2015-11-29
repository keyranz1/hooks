<?php

namespace Framework\Storage;


class File
{
    public static function exists($filePath){
        return file_exists( self::getRealPath($filePath) );
    }

    public static function getRealPath($filePath){
        $filePath = "/" . trim($filePath,"/"); //Making things easier

        //BasePath is not there
        if(strpos(strtolower($filePath),strtolower(BASE_DIR)) === false) {
            $filePath = BASE_DIR  . $filePath;
        }

        return  $filePath ;
    }

    public static function isDirectory($file){
        return is_dir($file);
    }

    public static function move($filePathFrom, $destinationDirectory){
        return move_uploaded_file ( $filePathFrom , $destinationDirectory );
    }

    public static function upload($filePath, $data){
        return file_put_contents( ( self::getRealPath($filePath) ) , $data );
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


}