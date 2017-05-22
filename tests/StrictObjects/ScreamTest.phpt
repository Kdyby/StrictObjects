<?php

/**
 * @testCase
 */

namespace KdybyTests\StrictObjects;

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ScreamTest extends \Tester\TestCase
{

	public function testMagicCall()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			$o->someBaZ();
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Call to undefined method KdybyTests\StrictObjects\SomeObject::someBaZ(), did you mean someBar()?');
	}

	public function testMagicStaticCall()
	{
		Assert::exception(function () {
			SomeObject::staBaz();
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Call to undefined static function KdybyTests\StrictObjects\SomeObject::staBaz(), did you mean staBar()?');
	}

	public function testMagicGet()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			$o->baz;
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot read an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}

	public function testMagicSet()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			$o->baz = 'value';
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot write to an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}

	public function testMagicUnset()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			unset($o->baz);
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot unset the property KdybyTests\StrictObjects\SomeObject::$baz.');
	}

	public function testMagicIsset()
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o) {
			isset($o->baz);
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot read an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}

}

(new ScreamTest())->run();
