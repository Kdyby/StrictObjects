<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

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
