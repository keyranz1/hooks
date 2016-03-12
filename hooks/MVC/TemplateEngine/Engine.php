<?php

namespace hooks\MVC\TemplateEngine;


use Razr\Lexer;
use Razr\Loader\LoaderInterface;
use Razr\Parser;

class IEngine extends \Razr\Engine
{


    /**
     * Constructor.
     *
     * @param LoaderInterface $loader
     * @param string          $cachePath
     */
    public function __construct(LoaderInterface $loader, $cachePath = null)
    {
        $this->loader    = $loader;
        $this->lexer     = new Lexer($this);
        $this->parser    = new Parser($this);
        $this->cachePath = $cachePath;

        $this->addExtension(new Extension);
    }

}