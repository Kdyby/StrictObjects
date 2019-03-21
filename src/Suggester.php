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

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @internal
 */
final class Suggester
{

	public static function suggestMethod(string $class, string $method): ?string
	{
		$rc = new ReflectionClass($class);
		return self::getSuggestion(
			array_diff(
				$rc->getMethods(ReflectionMethod::IS_PUBLIC),
				$rc->getMethods(ReflectionMethod::IS_STATIC)
			),
			$method
		);
	}

	public static function suggestStaticFunction(string $class, string $method): ?string
	{
		$rc = new ReflectionClass($class);
		return self::getSuggestion(
			array_intersect(
				$rc->getMethods(ReflectionMethod::IS_PUBLIC),
				$rc->getMethods(ReflectionMethod::IS_STATIC)
			),
			$method
		);
	}

	public static function suggestProperty(string $class, string $name): ?string
	{
		$rc = new ReflectionClass($class);
		return self::getSuggestion(
			array_diff(
				$rc->getProperties(ReflectionMethod::IS_PUBLIC),
				$rc->getProperties(ReflectionMethod::IS_STATIC)
			),
			$name
		);
	}

	/**
	 * Finds the best suggestion (for 8-bit encoding).
	 *
	 * @internal
	 */
	public static function getSuggestion(array $items, string $value): ?string
	{
		/** @var string $norm */
		$norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
		$best = NULL;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($items, SORT_REGULAR) as $reflector) {
			/** @var \ReflectionProperty|\ReflectionMethod|string $reflector */
			$item = ($reflector instanceof ReflectionProperty || $reflector instanceof ReflectionMethod) ? $reflector->getName() : (string) $reflector;

			if ($item !== $value && (
					($len = levenshtein($item, $value, 10, 11, 10)) < $min
					|| ($len = levenshtein((string) preg_replace($re, '', $item), $norm, 10, 11, 10) + 20) < $min
				)
			) {
				$min = $len;
				/** @var string $best */
				$best = $item;
			}
		}
		return $best;
	}

}
