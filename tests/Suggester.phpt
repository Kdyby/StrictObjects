<?php

/**
 * @testCase
 */

namespace KdybyTests\Doctrine\MagicAccessors;

use Kdyby\StrictObjects\Suggester;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class SuggesterTest extends Tester\TestCase
{

	/**
	 * length  allowed ins/del  replacements
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

}



(new SuggesterTest())->run();
