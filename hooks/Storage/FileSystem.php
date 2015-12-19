<?php

namespace hooks\Storage;


class FileSystem
{
    public static function exists($filePath){
        return file_exists( self::getRealPath($filePath) );
    }

    public static function getRealPath($filePath){

        $filePath = str_replace("/" , DIRECTORY_SEPARATOR, $filePath);
        $filePath = DIRECTORY_SEPARATOR . trim($filePath, DIRECTORY_SEPARATOR); //Making things easier

        //If BasePath is not there
        if(strpos(strtolower($filePath),strtolower(BASE_DIR)) === false) {
            $filePath = rtrim(BASE_DIR, DIRECTORY_SEPARATOR)  . $filePath;
        }
        //Replacing Double(s)
        $filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

        return  $filePath ;
    }

    public static function getBasePath($filePath){

        $filePath = str_replace("/" , DIRECTORY_SEPARATOR, $filePath);
        $filePath = DIRECTORY_SEPARATOR . trim($filePath, DIRECTORY_SEPARATOR); //Making things easier

        $filePath = explode(BASE_DIR, $filePath);
        $filePath =  end($filePath);

        //Replacing Double(s)
        $filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);
        return $filePath;
    }

    public static function isDirectory($file){
        return is_dir(self::getRealPath($file));
    }
    public static function isFile($file){
        return is_file(self::getRealPath($file));
    }

    public static function move($filePathFrom, $destinationDirectory){
        return move_uploaded_file ( self::getRealPath($filePathFrom) , self::getRealPath($destinationDirectory) );
    }

    public static function upload($filePath, $data){

        $realPath = self::getRealPath($filePath);
        $directory = dirname($realPath);
        if(!FileSystem::exists($directory)){
            FileSystem::makeDirectory($directory);
        }

        return file_put_contents( ( $realPath ) , $data );
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
                    @chmod(self::getRealPath($uploadPath), 0777);
                } catch (\Exception $e){
                    die("Error uploading file". $e->getMessage());
                }

            }
        }
        return $filesUploaded;
    }

    public static function get($path){
        $realPath = self::getRealPath($path);
        return file_get_contents($realPath);
    }

    public static function put($path, $data){
        $realPath = self::getRealPath($path);
        $directory = dirname($realPath);
        if(!FileSystem::exists($directory) || !FileSystem::isDirectory($directory)){
            FileSystem::makeDirectory($directory);
        }
        return file_put_contents($realPath, $data);
    }

    public static function delete($path){
        return unlink(self::getRealPath($path));
    }

    public static function makeDirectory($dir){
        try{
            mkdir ( $dir );
        } catch (\Exception $e){
            die("Error Making Directory file". $e->getMessage());
        }
    }

    public static function isImage($path){
        $type = @exif_imagetype(self::getRealPath($path)); //Support JPG, PNG, GIF choose your own
        return ($type >= 1 && $type <= 3);
    }

}