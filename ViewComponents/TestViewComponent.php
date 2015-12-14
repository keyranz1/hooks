<?php

namespace ViewComponents;

use hooks\MVC\ViewComponent;

class TestViewComponent extends ViewComponent
{

    public function __construct()
    {

        $this->registeredVariables = ["name" => "John"];

    }


}