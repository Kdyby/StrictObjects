<?php

declare(strict_types = 1);

/**
 * @testCase
 */

namespace KdybyTests\StrictObjects;

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ScreamTest extends \Tester\TestCase
{

	public function testMagicCall(): void
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o): void {
			$o->someBaZ();
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Call to undefined method KdybyTests\StrictObjects\SomeObject::someBaZ(), did you mean someBar()?');
	}

	public function testMagicStaticCall(): void
	{
		Assert::exception(function (): void {
			SomeObject::staBaz();
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Call to undefined static function KdybyTests\StrictObjects\SomeObject::staBaz(), did you mean staBar()?');
	}

	public function testMagicGet(): void
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o): void {
			$o->baz;
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot read an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}

	public function testMagicSet(): void
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o): void {
			$o->baz = 'value';
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot write to an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}

	public function testMagicUnset(): void
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o): void {
			unset($o->baz);
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot unset the property KdybyTests\StrictObjects\SomeObject::$baz.');
	}

	public function testMagicIsset(): void
	{
		$o = new SomeObject();

		Assert::exception(function () use ($o): void {
			isset($o->baz);
		}, \Kdyby\StrictObjects\MemberAccessException::class, 'Cannot read an undeclared property KdybyTests\StrictObjects\SomeObject::$baz, did you mean $bar?');
	}

}

(new ScreamTest())->run();
