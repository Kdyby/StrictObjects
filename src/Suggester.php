<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Kdyby\StrictObjects;

use Kdyby;
use Nette;



/**
 * @author Filip Procházka <filip@prochazka.su>
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
		return self::getSuggestion(
			get_class_methods($class),
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
		return self::getSuggestion(
			array_filter(
				get_class_methods($class),
				function ($m) use ($class) {
					return (new \ReflectionMethod($class, $m))->isStatic();
				}
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
		return self::getSuggestion(
			array_keys(get_class_vars($class)),
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
		foreach (array_unique($items) as $item) {
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
