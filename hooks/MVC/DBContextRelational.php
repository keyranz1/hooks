<?php

namespace hooks\MVC;


use hooks\Storage\FileSystem;

abstract class DBContextRelational
{

    public function __construct()
    {
        $path = "Models/.model-cache/" . $this->context . ".rln";
        if(FileSystem::exists($path)){
            $data = FileSystem::get($path);
            $relations = (array) @json_decode($data);
            $this->linkRelations($relations);
        }
    }

    private function linkRelations($relations){

        foreach ($relations as $relation => $destination){

            $params = [$this->$relation];

            $refClass = new \ReflectionClass($destination);
            $this->$relation = $refClass->newInstanceArgs((array) $params);

        }
    }

}