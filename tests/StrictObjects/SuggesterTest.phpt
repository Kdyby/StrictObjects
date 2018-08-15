<?php

declare(strict_types=1);

/**
 * @testCase
 */

namespace KdybyTests\StrictObjects;

use Kdyby\StrictObjects\Suggester;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class SuggesterTest extends TestCase
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
    public function dataSuggestion() : iterable
    {
        return [
            [null, [], ''],
            [null, [], 'a'],
            [null, ['a'], 'a'],
            ['a', ['a', 'b'], ''],
            ['b', ['a', 'b'], 'a'], // ignore 100% match
            ['a1', ['a1', 'a2'], 'a'], // take first
            [null, ['aaa', 'bbb'], 'a'],
            [null, ['aaa', 'bbb'], 'ab'],
            [null, ['aaa', 'bbb'], 'abc'],
            ['bar', ['foo', 'bar', 'baz'], 'baz'],
            ['abcd', ['abcd'], 'acbd'],
            ['abcd', ['abcd'], 'axbd'],
            [null, ['abcd'], 'axyd'], // 'tags' vs 'this'
            [null, ['setItem'], 'item'],
            ['setItem', ['setItem'], 'Item'],
            ['setItem', ['setItem'], 'addItem'],
            [null, ['addItem'], 'addItem'],
        ];
    }

    /**
     * @param string[] $items
     *
     * @dataProvider dataSuggestion
     */
    public function testGetSuggestion(?string $expected, array $items, string $value) : void
    {
        Assert::same($expected, Suggester::getSuggestion($items, $value));
    }

    /**
     * @return mixed[]
     */
    public function dataSuggestMethod() : iterable
    {
        return [
            ['someBar', 'someBaz'],
            [null, 'bar'],
            [null, 'Bar'],
            [null, 'staBaz'], // doesn't suggest static functions
        ];
    }

    /**
     * @dataProvider dataSuggestMethod
     */
    public function testSuggestMethod(?string $expected, string $calledMethod) : void
    {
        Assert::same($expected, Suggester::suggestMethod(SomeObject::class, $calledMethod));
    }

    /**
     * @return mixed[]
     */
    public function dataSuggestStaticFunction() : iterable
    {
        return [
            ['staBar', 'staBaz'],
            [null, 'someBaz'], // doesn't suggest methods
        ];
    }

    /**
     * @dataProvider dataSuggestStaticFunction
     */
    public function testSuggestStaticFunction(?string $expected, string $calledFunction) : void
    {
        Assert::same($expected, Suggester::suggestStaticFunction(SomeObject::class, $calledFunction));
    }

    /**
     * @return mixed[]
     */
    public function dataSuggestProperty() : iterable
    {
        return [
            ['bar', 'baz'],
            [null, 'nupe'], // doesn't suggest static properties
        ];
    }

    /**
     * @dataProvider dataSuggestProperty
     */
    public function testSuggestProperty(?string $expected, string $accessedProperty) : void
    {
        Assert::same($expected, Suggester::suggestProperty(SomeObject::class, $accessedProperty));
    }
}

(new SuggesterTest())->run();
