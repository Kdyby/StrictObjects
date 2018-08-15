<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use const SORT_REGULAR;
use function array_diff;
use function array_intersect;
use function array_unique;
use function levenshtein;
use function preg_replace;
use function strlen;

/**
 * @internal
 */
final class Suggester
{
    public static function suggestMethod(string $class, string $method) : ?string
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

    public static function suggestStaticFunction(string $class, string $method) : ?string
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

    public static function suggestProperty(string $class, string $name) : ?string
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
     * @param ReflectionProperty[]|ReflectionMethod[]|string[] $items
     * @param mixed                                            $value
     *
     * @internal
     */
    public static function getSuggestion(array $items, $value) : ?string
    {
        $norm = preg_replace($re = '#^(get|set|has|is|add)(?=[A-Z])#', '', $value);
        $best = null;
        $min  = (strlen($value) / 4 + 1) * 10 + .1;
        foreach (array_unique($items, SORT_REGULAR) as $item) {
            /** @var Reflector|string $item */
            $item = ($item instanceof ReflectionProperty || $item instanceof ReflectionMethod) ? $item->getName() : $item;

            if ($item === $value || (
                    ($len = levenshtein($item, $value, 10, 11, 10)) >= $min
                    && ($len = levenshtein(preg_replace($re, '', $item), $norm, 10, 11, 10) + 20) >= $min
                )
            ) {
                continue;
            }

            $min = $len;
            /** @var string $best */
            $best = $item;
        }
        return $best;
    }
}
