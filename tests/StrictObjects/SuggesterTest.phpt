<?php

/**
 * @testCase
 */

namespace KdybyTests\StrictObjects;

use Kdyby\StrictObjects\Suggester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class SuggesterTest extends \Tester\TestCase
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
	public function dataSuggestion()
	{
		return [
			[NULL, [], ''],
			[NULL, [], 'a'],
			[NULL, ['a'], 'a'],
			['a', ['a', 'b'], ''],
			['b', ['a', 'b'], 'a'], // ignore 100% match
			['a1', ['a1', 'a2'], 'a'], // take first
			[NULL, ['aaa', 'bbb'], 'a'],
			[NULL, ['aaa', 'bbb'], 'ab'],
			[NULL, ['aaa', 'bbb'], 'abc'],
			['bar', ['foo', 'bar', 'baz'], 'baz'],
			['abcd', ['abcd'], 'acbd'],
			['abcd', ['abcd'], 'axbd'],
			[NULL, ['abcd'], 'axyd'], // 'tags' vs 'this'
			[NULL, ['setItem'], 'item'],
			['setItem', ['setItem'], 'Item'],
			['setItem', ['setItem'], 'addItem'],
			[NULL, ['addItem'], 'addItem'],
		];
	}

	/**
	 * @dataProvider dataSuggestion
	 */
	public function testGetSuggestion($expected, $items, $value)
	{
		Assert::same($expected, Suggester::getSuggestion($items, $value));
	}

	public function dataSuggestMethod()
	{
		return [
			['someBar', 'someBaz'],
			[NULL, 'bar'],
			[NULL, 'Bar'],
			[NULL, 'staBaz'], // doesn't suggest static functions
		];
	}

	/**
	 * @dataProvider dataSuggestMethod
	 */
	public function testSuggestMethod($expected, $calledMethod)
	{
		Assert::same($expected, Suggester::suggestMethod(SomeObject::class, $calledMethod));
	}

	public function dataSuggestStaticFunction()
	{
		return [
			['staBar', 'staBaz'],
			[NULL, 'someBaz'], // doesn't suggest methods
		];
	}

	/**
	 * @dataProvider dataSuggestStaticFunction
	 */
	public function testSuggestStaticFunction($expected, $calledFunction)
	{
		Assert::same($expected, Suggester::suggestStaticFunction(SomeObject::class, $calledFunction));
	}

	public function dataSuggestProperty()
	{
		return [
			['bar', 'baz'],
			[NULL, 'nupe'], // doesn't suggest static properties
		];
	}

	/**
	 * @dataProvider dataSuggestProperty
	 */
	public function testSuggestProperty($expected, $accessedProperty)
	{
		Assert::same($expected, Suggester::suggestProperty(SomeObject::class, $accessedProperty));
	}

}

(new SuggesterTest())->run();
