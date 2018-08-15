<?php

declare(strict_types=1);

namespace KdybyTests\StrictObjects;

use Kdyby\StrictObjects\Suggester;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use function preg_replace;

final class SuggesterTest extends TestCase
{
    /**
     * Suggestions table
     *
     * Length  Allowed ins/del  Replacements
     * -------------------------------------
     * 0       1                0
     * 1       1                1
     * 2       1                1
     * 3       1                1
     * 4       2                1
     * 5       2                2
     * 6       2                2
     * 7       2                2
     * 8       3                2
     *
     * @return mixed[]
     */
    public function dataSuggestionProvider() : iterable
    {
        $noopNormalizer        = function (string $s) : string {
            return $s;
        };
        $unprefixingNormalizer = function (string $name) : string {
            return preg_replace('~^(?:get|set|has|is|add)(?=[A-Z])~', '', $name);
        };

        yield [null, [], '', $noopNormalizer];
        yield [null, [], 'a', $noopNormalizer];
        yield [null, ['a'], 'a', $noopNormalizer];
        yield ['a', ['a', 'b'], '', $noopNormalizer];
        yield ['b', ['a', 'b'], 'a', $noopNormalizer]; // ignore 100% match
        yield ['a1', ['a1', 'a2'], 'a', $noopNormalizer]; // take first
        yield [null, ['aaa', 'bbb'], 'a', $noopNormalizer];
        yield [null, ['aaa', 'bbb'], 'ab', $noopNormalizer];
        yield [null, ['aaa', 'bbb'], 'abc', $noopNormalizer];
        yield ['bar', ['foo', 'bar', 'baz'], 'baz', $noopNormalizer];
        yield ['abcd', ['abcd'], 'acbd', $noopNormalizer];
        yield ['abcd', ['abcd'], 'axbd', $noopNormalizer];
        yield [null, ['abcd'], 'axyd', $noopNormalizer]; // 'tags' vs 'this'
        yield [null, ['setItem'], 'item', $unprefixingNormalizer];
        yield ['setItem', ['setItem'], 'Item', $unprefixingNormalizer];
        yield ['setItem', ['setItem'], 'addItem', $unprefixingNormalizer];
        yield [null, ['addItem'], 'addItem', $unprefixingNormalizer];
    }

    /**
     * @param string[] $items
     *
     * @dataProvider dataSuggestionProvider()
     */
    public function testGetSuggestion(?string $expected, array $items, string $value, callable $normalizer) : void
    {
        $methodReflection = new ReflectionMethod(Suggester::class, 'getSuggestion');
        $methodReflection->setAccessible(true);

        self::assertSame($expected, $methodReflection->invokeArgs(null, [$items, $value, $normalizer]));
    }

    /**
     * @return mixed[]
     */
    public function dataSuggestPropertyProvider() : iterable
    {
        yield ['bar', 'baz'];
        yield [null, 'nupe']; // doesn't suggest static properties
    }

    /**
     * @dataProvider dataSuggestPropertyProvider()
     */
    public function testSuggestProperty(?string $expected, string $accessedProperty) : void
    {
        self::assertSame($expected, Suggester::suggestProperty(new SomeObject(), $accessedProperty));
    }

    /**
     * @return mixed[]
     */
    public function dataSuggestInstanceMethodProvider() : iterable
    {
        yield ['someBar', 'someBaz'];
        yield [null, 'bar'];
        yield [null, 'Bar'];
        yield [null, 'staBaz']; // doesn't suggest static functions
    }

    /**
     * @dataProvider dataSuggestInstanceMethodProvider()
     */
    public function testSuggestInstanceMethod(?string $expected, string $calledMethod) : void
    {
        self::assertSame($expected, Suggester::suggestInstanceMethod(new SomeObject(), $calledMethod));
    }

    /**
     * @return mixed[]
     */
    public function dataSuggestStaticMethodProvider() : iterable
    {
        yield ['staBar', 'staBaz'];
        yield [null, 'someBaz']; // doesn't suggest methods
    }

    /**
     * @dataProvider dataSuggestStaticMethodProvider()
     */
    public function testSuggestStaticFunction(?string $expected, string $calledFunction) : void
    {
        self::assertSame($expected, Suggester::suggestStaticMethod(SomeObject::class, $calledFunction));
    }
}
