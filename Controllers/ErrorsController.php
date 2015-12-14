<?php

namespace Controllers;


class ErrorsController
{

    public function error404(){
        return view("errors/404")->title("Oops!!! 404 Error");
    }

    public function error500(){
        return view("errors/500")->title("Oops!!! 500 Error");
    }


}