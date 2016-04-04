<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\StrictObjects;



/**
 * @author Filip Procházka <filip@prochazka.su>
 * @author David Grudl <https://davidgrudl.com>
 */
trait Scream
{

	/**
	 * Call to undefined method.
	 *
	 * @param string $name method name
	 * @param array $args arguments
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public function __call($name, $args)
	{
		$class = method_exists($this, $name) ? 'parent' : get_class($this);
		$hint = Suggester::suggestMethod(get_class($this), $name);
		throw new MemberAccessException("Call to undefined method $class::$name()" . ($hint ? ", did you mean $hint()?" : '.'));
	}



	/**
	 * Call to undefined static method.
	 *
	 * @param string $name method name (in lower case!)
	 * @param array $args arguments
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public static function __callStatic($name, $args)
	{
		$class = get_called_class();
		$hint = Suggester::suggestStaticFunction($class, $name);
		throw new MemberAccessException("Call to undefined static function $class::$name()" . ($hint ? ", did you mean $hint()?" : '.'));
	}



	/**
	 * Returns property value. Do not call directly.
	 *
	 * @param string $name property name
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public function &__get($name)
	{
		$class = get_class($this);
		$hint = Suggester::suggestProperty($class, $name);
		throw new MemberAccessException("Cannot read an undeclared property $class::\$$name" . ($hint ? ", did you mean \$$hint?" : '.'));
	}



	/**
	 * Sets value of a property. Do not call directly.
	 *
	 * @param string $name property name
	 * @param mixed $value property value
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 * @return void
	 */
	public function __set($name, $value)
	{
		$class = get_class($this);
		$hint = Suggester::suggestProperty($class, $name);
		throw new MemberAccessException("Cannot write to an undeclared property $class::\$$name" . ($hint ? ", did you mean \$$hint?" : '.'));
	}



	/**
	 * Is property defined?
	 *
	 * @param string $name property name
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 * @return bool
	 */
	public function __isset($name)
	{
		$class = get_class($this);
		$hint = Suggester::suggestProperty($class, $name);
		throw new MemberAccessException("Cannot read an undeclared property $class::\$$name" . ($hint ? ", did you mean \$$hint?" : '.'));
	}



	/**
	 * Access to undeclared property.
	 *
	 * @param string $name property name
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 * @return void
	 */
	public function __unset($name)
	{
		$class = get_class($this);
		throw new MemberAccessException("Cannot unset the property $class::\$$name.");
	}

}
