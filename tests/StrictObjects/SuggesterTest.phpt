<?php

declare(strict_types = 1);

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
	public function dataSuggestion(): array
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
	public function testGetSuggestion(?string $expected, array $items, string $value): void
	{
		Assert::same($expected, Suggester::getSuggestion($items, $value));
	}

	public function dataSuggestMethod(): array
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
	public function testSuggestMethod(?string $expected, string $calledMethod): void
	{
		Assert::same($expected, Suggester::suggestMethod(SomeObject::class, $calledMethod));
	}

	public function dataSuggestStaticFunction(): array
	{
		return [
			['staBar', 'staBaz'],
			[NULL, 'someBaz'], // doesn't suggest methods
		];
	}

	/**
	 * @dataProvider dataSuggestStaticFunction
	 */
	public function testSuggestStaticFunction(?string $expected, string $calledFunction): void
	{
		Assert::same($expected, Suggester::suggestStaticFunction(SomeObject::class, $calledFunction));
	}

	public function dataSuggestProperty(): array
	{
		return [
			['bar', 'baz'],
			[NULL, 'nupe'], // doesn't suggest static properties
		];
	}

	/**
	 * @dataProvider dataSuggestProperty
	 */
	public function testSuggestProperty(?string $expected, string $accessedProperty): void
	{
		Assert::same($expected, Suggester::suggestProperty(SomeObject::class, $accessedProperty));
	}

}

(new SuggesterTest())->run();
