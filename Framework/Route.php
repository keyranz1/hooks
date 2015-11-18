<?php

namespace Framework;

use Framework\Social\Instagram;

class Route {

    public static  $partialsRegistered = [];

    public static function flush(){
        if(View::$api){
            self::flushAPI();
        } else {
            self::flushView();
        }
    }


    public static function flushAPI(){

        $data = [];

        foreach (self::$partialsRegistered as $partial){
            $data[] = $partial->data;
        }
        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
    }



    public static function flushView(){

        if(View::$headers){
            $hdr =  BASE_DIR.'/views/inc/header.inc.php';
            self::inject($hdr, ["title" => View::$title, "metaContent" => View::$metaContent, "author" => View::$author]);
        }

        foreach (self::$partialsRegistered as $partial){
            self::inject($partial->view, $partial->data);
        }

        if(View::$headers) {
            $ftr = BASE_DIR . '/views/inc/footer.inc.php';
/*
            $recentBlogs = DB::get("posts", null, null, ["createDate" => "ASC"], "App\\Blog\\Post");
            $ig = new Instagram();
            $instagramImages = $ig
                ->user(INSTAGRAM_USER_ID)
                ->count(4)
                ->getPosts();

            self::inject($ftr, ["recentBlogs" => $recentBlogs,
                                "instagramImages" => $instagramImages]);
     */                           
            
            self::inject($ftr);
        }

    }

    public static function inject($view, $data = []){
        extract($data);
        if(file_exists($view)){
            include($view);
        }
    }

    public static function view($template = null, $data = []){


        if($template){
            //Changing main.folder.file to main/folder/file.php
            $template = str_replace('.','/',$template);
            $template = BASE_DIR.'/views/' . $template . '.php';

            if(file_exists($template)){

                $temp = new \stdClass();
                $temp->view = $template;
                $temp->data = $data;
                self::$partialsRegistered[] = $temp;

            } else {
                $debug = debug_backtrace();
                if(isset($debug[0])){
                    die("Failed to include file <strong>" . $template . "</strong>"
                        ." at file <strong>". $debug[0]["file"] ."</strong>"
                        ." in line " . $debug[0]["line"] .".");
                }
                Redirect::trigger(404);
            }
        }

    }

    public static function viewInclude($template){

        //Changing main.folder.file to main/folder/file.php
        $template = str_replace('.','/',$template);
        $template = BASE_DIR.'/views/' . $template . '.php';

        if(file_exists($template)){
            include_once $template;

        } else {
            $debug = debug_backtrace();
            if(isset($debug[0])){
                die("Failed to include file <strong>" . $template . "</strong>"
                    ." at file <strong>". $debug[0]["file"] ."</strong>"
                    ." in line " . $debug[0]["line"] .".");
            }
            Redirect::trigger(404);
        }

    }

    public static function get($method, $data = []){

        $method = explode("@", $method);

        $_method = $method[0];
        $_controller = "Controllers\\" . $method[1];

        $object = new $_controller;
        if(method_exists($_controller,$_method)){
            call_user_func_array(array($object, $_method), $data);

            if(isset($_GET["api"]) && $_GET["api"] == "true"){
                View::$api = true;
            }

            self::flush();
        } else {
            Redirect::trigger(500);
        }
    }


}