<?php

    namespace Controllers;

    use hooks\MVC\Controller;

    class HomeController extends Controller{


        public function index(){
            return view()->pass(["app" => app()]);
        }

        public function doc(){
            return view();
        }

        public function image(){
            return view("home/image");
        }

        public function test(){
            return view();
        }


    }