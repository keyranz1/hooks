<?php

namespace ViewComponents;

use Framework\MVC\ViewComponent;

class TestViewComponent extends ViewComponent
{

    public function __construct()
    {

        $this->registeredVariables = ["name" => "John"];

    }


}