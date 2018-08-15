<?php

namespace KdybyTests\StrictObjects;

class SomeObject
{

	use \Kdyby\StrictObjects\Scream;

	/**
	 * @var mixed
	 */
	public $foo;

	/**
	 * @var mixed
	 */
	public $bar;

	/**
	 * @var mixed
	 */
	public static $nope;

	public function someBar()
	{
	}

	public function someFoo()
	{
	}

	public static function staFoo()
	{
	}

	public static function staBar()
	{
	}

}
