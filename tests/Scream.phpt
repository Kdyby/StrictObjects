<?php

/**
 * @testCase
 */

namespace KdybyTests\Doctrine\MagicAccessors;

use KdybyTests\StrictObjects\SomeObject;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ScreamTest extends Tester\TestCase
{

	public function testMagicCall()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			$o->someBaZ();
		}, 'Kdyby\StrictObjects\MemberAccessException', 'Call to undefined method KdybyTests\StrictObjects\SomeObject::someBaZ(), did you mean someBar()?');
	}



	public function testMagicStaticCall()
	{
		Assert::exception(function () {
			SomeObject::staBaz();
		}, 'Kdyby\StrictObjects\MemberAccessException', 'Call to undefined static function KdybyTests\StrictObjects\SomeObject::staBaz(), did you mean staBar()?');
	}



	public function testMagicGet()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			$o->baz;
		}, 'Kdyby\StrictObjects\MemberAccessException', 'Cannot read an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}



	public function testMagicSet()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			$o->baz = 'value';
		}, 'Kdyby\StrictObjects\MemberAccessException', 'Cannot write to an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}



	public function testMagicUnset()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			unset($o->baz);
		}, 'Kdyby\StrictObjects\MemberAccessException', 'Cannot unset the property KdybyTests\StrictObjects\SomeObject::$baz.');
	}



	public function testMagicIsset()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			isset($o->baz);
		}, 'Kdyby\StrictObjects\MemberAccessException', 'Cannot read an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}

}



(new ScreamTest())->run();
