<?php


namespace Framework\MVC;


use Framework\Storage\File;
use Framework\Storage\Globals;
use Razr\Engine;
use Razr\Loader\FilesystemLoader;

class View extends MinifyHelper
{

    private $title = BASE_URL,
            $metaContent = "",
            $author = "",
            $keywords = "",
            $registeredVariables = [];

    public $view;

    public function __construct($view = null, $vars = [])
    {
        $this->view = $view;
        $this->pass($vars);
    }

    public function prepare()
    {

    }

    public function render(){

        if($this->view === null){
            self::includeMVCView();
        } else {
            $filePath = BASE_DIR . "/views/" .  $this->view . ".php";
            self::includeRequestedView($filePath);
        }

    }

    private function includeRequestedView($filePath){
        if(File::exists($filePath)){
            self::printView($filePath);
        }
    }

    private function includeMVCView(){

        $controller = strtolower(Globals::getItem("controller"));
        $method = strtolower(Globals::getItem("method"));

        $filePath = BASE_DIR . "/views/" .  $controller . "/" . $method . ".php";
        self::includeRequestedView($filePath);
    }


    public function printView($filePath){
        $controller = strtolower(Globals::getItem("controller"));
        $paths = [
            BASE_DIR . "/views/_shared",
            BASE_DIR . "/views/" . $controller
        ];
        $razr = new Engine(new FilesystemLoader($paths), BASE_DIR . "/views/.razr-cache" );

        $page = new \stdClass();
        $page->title = $this->title;
        $page->author = $this->author;
        $page->metaContent = $this->metaContent;

        $this->registeredVariables["page"] = $page;

        $output = $razr->render($filePath, $this->registeredVariables);
        echo self::compress($output);
    }


    public function title($title)
    {
        $this->title = $title;
        return $this;
    }


    public function meta($metaContent)
    {
        $this->metaContent = $metaContent;
        return $this;
    }


    public function author($author)
    {
        $this->author = $author;
        return $this;
    }


    public function keywords($keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }


    public function pass($vars)
    {
        foreach($vars as $key => $val){
            $this->registeredVariables[$key] = $val;
        }
        return $this;
    }






}