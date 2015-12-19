<?php

namespace Models\Context;

use hooks\MVC\DBContext;

class Item extends DBContext {

	public $id, $name, $list, $timestamp;

	protected $context = "item";

	protected $contextPrimaryKey = "id";

	public function __construct( $key = null)
	{
		parent::__construct($key);
	}

}