<?php

namespace ViewComponents;

use Framework\MVC\ViewComponent;

class CustomViewComponent extends ViewComponent
{

    public function __construct()
    {

        $this->registeredVariables = ["name" => "John"];
        $this->componentFile = "other";
    }


}