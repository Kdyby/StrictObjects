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
 * @internal
 */
final class Suggester
{

	/**
	 * @param string $class
	 * @param string $method
	 * @return NULL|string
	 */
	public static function suggestMethod($class, $method)
	{
		$rc = new \ReflectionClass($class);
		return self::getSuggestion(
			array_diff(
				$rc->getMethods(\ReflectionMethod::IS_PUBLIC),
				$rc->getMethods(\ReflectionMethod::IS_STATIC)
			),
			$method
		);
	}



	/**
	 * @param string $class
	 * @param string $method
	 * @return NULL|string
	 */
	public static function suggestStaticFunction($class, $method)
	{
		$rc = new \ReflectionClass($class);
		return self::getSuggestion(
			array_intersect(
				$rc->getMethods(\ReflectionMethod::IS_PUBLIC),
				$rc->getMethods(\ReflectionMethod::IS_STATIC)
			),
			$method
		);
	}



	/**
	 * @param string $class
	 * @param string $name
	 * @return NULL|string
	 */
	public static function suggestProperty($class, $name)
	{
		$rc = new \ReflectionClass($class);
		return self::getSuggestion(
			array_diff(
				$rc->getProperties(\ReflectionMethod::IS_PUBLIC),
				$rc->getProperties(\ReflectionMethod::IS_STATIC)
			),
			$name
		);
	}



	/**
	 * Finds the best suggestion (for 8-bit encoding).
	 *
	 * @author David Grudl (https://davidgrudl.com)
	 * @license See https://nette.org/en/license
	 * @return string|NULL
	 * @internal
	 */
	public static function getSuggestion(array $items, $value)
	{
		$norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
		$best = NULL;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($items, SORT_REGULAR) as $item) {
			/** @var \ReflectionProperty|\ReflectionMethod|string $item */
			$item = $item instanceof \Reflector ? $item->getName() : $item;
			if ($item !== $value && (
					($len = levenshtein($item, $value, 10, 11, 10)) < $min
					|| ($len = levenshtein(preg_replace($re, '', $item), $norm, 10, 11, 10) + 20) < $min
				)
			) {
				$min = $len;
				$best = $item;
			}
		}
		return $best;
	}

}
