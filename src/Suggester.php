<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use ReflectionClass;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use RuntimeException;

use function array_diff;
use function array_intersect;
use function array_map;
use function array_unique;
use function assert;
use function levenshtein;
use function preg_last_error;
use function preg_replace;
use function sprintf;
use function strlen;

use const PREG_NO_ERROR;
use const SORT_REGULAR;

/**
 * @internal
 */
final class Suggester
{
    public static function suggestProperty(object $object, string $property): ?string
    {
        $reflection = new ReflectionObject($object);

        return self::getPropertySuggestion(
            array_diff(
                $reflection->getProperties(ReflectionMethod::IS_PUBLIC),
                $reflection->getProperties(ReflectionMethod::IS_STATIC)
            ),
            $property
        );
    }

    public static function suggestInstanceMethod(object $object, string $method): ?string
    {
        $reflection = new ReflectionObject($object);

        return self::getMethodSugestion(
            array_diff(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                $reflection->getMethods(ReflectionMethod::IS_STATIC)
            ),
            $method
        );
    }

    /**
     * @phpstan-param class-string $class
     */
    public static function suggestStaticMethod(string $class, string $method): ?string
    {
        $reflection = new ReflectionClass($class);

        return self::getMethodSugestion(
            array_intersect(
                $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
                $reflection->getMethods(ReflectionMethod::IS_STATIC)
            ),
            $method
        );
    }

    /**
     * @param ReflectionProperty[] $properties
     */
    private static function getPropertySuggestion(array $properties, string $name): ?string
    {
        return self::getSuggestion(
            array_map(
                static function (ReflectionProperty $property): string {
                    return $property->getName();
                },
                $properties
            ),
            $name,
            static function (string $name): string {
                return $name;
            }
        );
    }

    /**
     * @param ReflectionMethod[] $methods
     */
    private static function getMethodSugestion(array $methods, string $name): ?string
    {
        return self::getSuggestion(
            array_map(
                static function (ReflectionMethod $method): string {
                    return $method->getName();
                },
                $methods
            ),
            $name,
            static function (string $name): string {
                $normalized = preg_replace('~^(?:get|set|has|is|add)(?=[A-Z])~', '', $name);

                if (preg_last_error() !== PREG_NO_ERROR) {
                    throw new RuntimeException(sprintf('Error building method suggestion: %s', preg_last_error()));
                }

                assert($normalized !== null);

                return $normalized;
            }
        );
    }

    /**
     * Finds the best suggestion (for 8-bit encoding).
     *
     * @param string[]                  $candidateNames
     * @param mixed                     $name
     * @param callable(string) : string $normalizer
     */
    private static function getSuggestion(array $candidateNames, $name, callable $normalizer): ?string
    {
        $normalizedName = $normalizer($name);
        $best           = null;
        $min            = (strlen($name) / 4 + 1) * 10 + .1;

        foreach (array_unique($candidateNames, SORT_REGULAR) as $candidateName) {
            if ($candidateName === $name) {
                continue;
            }

            $realDistance       = levenshtein($candidateName, $name, 10, 11, 10);
            $normalizedDistance = levenshtein($normalizer($candidateName), $normalizedName, 10, 11, 10) + 20;

            if ($realDistance >= $min && $normalizedDistance >= $min) {
                continue;
            }

            $min  = $realDistance < $min ? $realDistance : $normalizedDistance;
            $best = $candidateName;
        }

        return $best;
    }
}
