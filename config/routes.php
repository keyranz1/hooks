<?php

define("routes",
[
    "/"=> "Home/index",
    "404"=> "Errors/error404",
    "tests/{file}" => "home/tests",
    "services/{file}" => "home/services",
    "image/{file}" => "Image/render",
]);

