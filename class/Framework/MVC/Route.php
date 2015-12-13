<?php

namespace Framework\MVC;

use Framework\Storage\Globals;

class Route{

    private static $routes = [];

    public function deliver(){

        $uri = $this->projectURI();

        if(!$this->routeCustomRoutes($uri)){
            if(!$this->routeMVC($uri)){
                Redirect::trigger(404);
            }
        }
    }

    private function routeCustomRoutes($uri){

        foreach (self::$routes as $alias => $path){

            $alias = trim($alias,"/");

            //Step 1 of 2: Matching Hard-Codes URLS
            if($alias == $uri){
                $this->routeMVC($path);
                return true;
            }

            //Step 2 of 2: Matching Pattens

            if($this->match($alias,$uri)){
                return true;
            }

        }

        return false;
    }

    public function routeMVC($path, $vars = []){
        $path = trim($path,"/");
        $paths = explode("/",$path);

        if(count($paths) === 2){

            $controller = $paths[0];
            $method = $paths[1];

            $controllerPath = "Controllers\\" . ucfirst($controller) . "Controller";

            if($this->controllerExists($controller, $method)){

                Globals::setItem("controller",$controller);
                Globals::setItem("method", $method);

                $this->injectMVC($controllerPath,$method,$vars);

                return true;

            }
        }

        return false;
    }

    private function controllerExists($controller, $method){
        if (file_exists(BASE_DIR . "/class/controllers/" . ucfirst($controller) . "Controller.php" )){
            $controllerPath = "Controllers\\" . ucfirst($controller) . "Controller";
            if(method_exists($controllerPath,$method)){
                return true;
            }
        }
        return false;
    }

    private function injectMVC($controller, $method, $vars){
        $object = new $controller;
        if(method_exists($controller,$method)){
            $view = call_user_func_array(array($object, $method), $vars);

            if( is_object($view) && get_class($view) === "Framework\\MVC\\View"){
                $view->render();
            } else {
                print_r($view);
            }

        } else {
            Redirect::trigger(500);
        }
    }

    private function match($alias, $uri){
        if($this->isValidAliasForRegEx($alias, $uri)){

            $variablesToFetchFromURL = $this->variablesToFetchFromURL($alias);

            if(count($variablesToFetchFromURL) > 0){

                $_alias = $alias;
                foreach($variablesToFetchFromURL as $var){
                    $_alias = str_replace( "{".  $var. "}","$",$_alias);
                }
                $variablesFetched = $this->extractVariablesFromURL($_alias,$uri);


                $matchedVariables = [];

                if(count($variablesFetched) === count($variablesToFetchFromURL)){
                    for($i = 0; $i < count($variablesFetched); $i++){
                        $variableName = $variablesToFetchFromURL[$i];
                        $variableValue = $variablesFetched[$i];
                        $matchedVariables[$variableName] = $variableValue;
                    }

                    $aliasedMVCRoute = self::$routes[$alias];
                    $this->routeMVC($aliasedMVCRoute, $matchedVariables);

                    return true;
                }
            }


        }

        return false;
    }

    private function isValidAliasForRegEx($alias, $uri){
        return
            $alias !== "" &&                                                //Not Empty
            substr_count($uri, "/") ===  substr_count($alias, "/") &&       //Both have equal /
            substr_count($alias, "{") !== 0 &&                              //Has at least one {
            (substr_count($alias, "{") == substr_count($alias, "}"));       //Has equal number of { and }


    }

    private function variablesToFetchFromURL($alias){
        $expression = "/{([a-zA-Z0-9-:]+)}/";
        preg_match_all($expression, $alias, $matches);

        return $matches[1];
    }

    private function extractVariablesFromURL($pattern,$input){
        $delimiter = rand();
        while (strpos($input,$delimiter) !== false) {
            $delimiter++;
        }

        $exps = explode("$",$pattern);
        foreach($exps as $exp){
            $input = str_replace($exp,",", $input);
        }

        $responses = explode(",", $input);
        array_shift($responses);
        return $responses;
    }

    public function setRoutes($routes)
    {
        self::$routes = $routes;
        return $this;
    }

    private function projectURI(){
        $realPath = parse_url($this->completeURL(), PHP_URL_PATH);
        $projectFolder = basename(BASE_DIR);
        $paths = explode($projectFolder,$realPath);

        $projectURI = $paths[count($paths) -1];
        return trim($projectURI,"/");

    }

    private function completeURL(){
        return trim($_SERVER['REQUEST_SCHEME'] . "://" .$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],"/");
    }

}