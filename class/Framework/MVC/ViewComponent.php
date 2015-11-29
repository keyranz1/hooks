<?php

namespace Framework\MVC;


use Razr\Engine;
use Razr\Loader\FilesystemLoader;

class ViewComponent extends MinifyHelper
{

    protected $registeredVariables = [];
    protected $componentFile;

    public function invoke(){

        if($this->componentFile === null){
            $this->componentFile = $this->getViewComponentDefaultView();
        }

        $paths = [
            BASE_DIR . "/views/_components"
        ];
        $razr = new Engine(new FilesystemLoader($paths), BASE_DIR . "/views/.razr-cache" );

        $output = $razr->render($this->componentFile, $this->registeredVariables);
        echo self::compress($output);
    }


    protected function getViewComponentDefaultView(){

        $function = new \ReflectionClass($this);
        $realClass = $function->getShortName();

        //TestViewComponent => test

        return strtolower(explode("ViewComponent",$realClass)[0]);

    }


}