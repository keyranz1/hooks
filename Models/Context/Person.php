<?php

namespace Models\Context;

use hooks\MVC\DBContext;

class Person extends DBContext {

	public $id, $name, $image, $item, $timestamp;

	protected $context = "person";

	protected $contextPrimaryKey = "id";

	public function __construct( $key = null)
	{
		parent::__construct($key);
	}

}