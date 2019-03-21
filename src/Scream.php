<?php

declare(strict_types = 1);

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\StrictObjects;

trait Scream
{

	/**
	 * Call to undefined method.
	 *
	 * @param string $name method name
	 * @param array $args arguments
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public function __call(string $name, array $args)
	{
		$class = method_exists($this, $name) ? 'parent' : get_class($this);
		$hint = Suggester::suggestMethod(get_class($this), $name);
		throw new \Kdyby\StrictObjects\MemberAccessException(sprintf(
			'Call to undefined method %s::%s()%s',
			$class,
			$name,
			$hint !== NULL ? sprintf(', did you mean %s()?', $hint) : '.'
		));
	}

	/**
	 * Call to undefined static method.
	 *
	 * @param string $name method name (in lower case!)
	 * @param array $args arguments
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public static function __callStatic(string $name, array $args)
	{
		$class = get_called_class();
		$hint = Suggester::suggestStaticFunction($class, $name);
		throw new \Kdyby\StrictObjects\MemberAccessException(sprintf(
			'Call to undefined static function %s::%s()%s',
			$class,
			$name,
			$hint !== NULL ? sprintf(', did you mean %s()?', $hint) : '.'
		));
	}

	/**
	 * Returns property value. Do not call directly.
	 *
	 * @param string $name property name
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public function &__get(string $name)
	{
		$class = get_class($this);
		$hint = Suggester::suggestProperty($class, $name);
		throw new \Kdyby\StrictObjects\MemberAccessException(sprintf(
			'Cannot read an undeclared property %s::$%s%s',
			$class,
			$name,
			$hint !== NULL ? sprintf(', did you mean $%s?', $hint) : '.'
		));
	}

	/**
	 * Sets value of a property. Do not call directly.
	 *
	 * @param string $name property name
	 * @param mixed $value property value
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public function __set(string $name, $value)
	{
		$class = get_class($this);
		$hint = Suggester::suggestProperty($class, $name);
		throw new \Kdyby\StrictObjects\MemberAccessException(sprintf(
			'Cannot write to an undeclared property %s::$%s%s',
			$class,
			$name,
			$hint !== NULL ? sprintf(', did you mean $%s?', $hint) : '.'
		));
	}

	/**
	 * Is property defined?
	 *
	 * @param string $name property name
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public function __isset(string $name): bool
	{
		$class = get_class($this);
		$hint = Suggester::suggestProperty($class, $name);
		throw new \Kdyby\StrictObjects\MemberAccessException(sprintf(
			'Cannot read an undeclared property %s::$%s%s',
			$class,
			$name,
			$hint !== NULL ? sprintf(', did you mean $%s?', $hint) : '.'
		));
	}

	/**
	 * Access to undeclared property.
	 *
	 * @param string $name property name
	 * @throws \Kdyby\StrictObjects\MemberAccessException
	 */
	public function __unset(string $name)
	{
		$class = get_class($this);
		throw new \Kdyby\StrictObjects\MemberAccessException(sprintf('Cannot unset the property %s::$%s.', $class, $name));
	}

}
