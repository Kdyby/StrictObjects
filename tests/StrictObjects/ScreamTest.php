<?php

declare(strict_types=1);

namespace KdybyTests\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedMethod;
use Kdyby\StrictObjects\Exception\UndefinedProperty;
use PHPUnit\Framework\TestCase;

use function sprintf;

final class ScreamTest extends TestCase
{
    private SomeObject $object;

    protected function setUp(): void
    {
        $this->object = new SomeObject();
    }

    /**
     * @return mixed[]
     */
    public function instanceMethodNamesProvider(): iterable
    {
        yield ['someBaZ', 'someBar'];
        yield ['totallyAlone', null];
    }

    /**
     * @dataProvider instanceMethodNamesProvider()
     */
    public function testMagicCall(string $methodName, ?string $suggestion): void
    {
        $message = $suggestion !== null
            ? sprintf('Call to an undefined instance method %s::%s(), did you mean %s()?', SomeObject::class, $methodName, $suggestion)
            : sprintf('Call to an undefined instance method %s::%s().', SomeObject::class, $methodName);

        $this->expectException(UndefinedMethod::class);
        $this->expectExceptionMessage($message);

        $this->object->$methodName();
    }

    /**
     * @return mixed[]
     */
    public function staticMethodNamesProvider(): iterable
    {
        yield ['staBaZ', 'staBar'];
        yield ['totallyAlone', null];
    }

    /**
     * @dataProvider staticMethodNamesProvider()
     */
    public function testMagicStaticCall(string $methodName, ?string $suggestion): void
    {
        $message = $suggestion !== null
            ? sprintf('Call to an undefined static method %s::%s(), did you mean %s()?', SomeObject::class, $methodName, $suggestion)
            : sprintf('Call to an undefined static method %s::%s().', SomeObject::class, $methodName);

        $this->expectException(UndefinedMethod::class);
        $this->expectExceptionMessage($message);

        SomeObject::$methodName();
    }

    /**
     * @return mixed[]
     */
    public function propertyNamesProvider(): iterable
    {
        yield ['baz', 'bar'];
        yield ['totallyAlone', null];
    }

    /**
     * @dataProvider propertyNamesProvider()
     */
    public function testMagicGet(string $propertyName, ?string $suggestion): void
    {
        $message = $suggestion !== null
            ? sprintf('Cannot read an undeclared property %s::$%s, did you mean $%s?', SomeObject::class, $propertyName, $suggestion)
            : sprintf('Cannot read an undeclared property %s::$%s.', SomeObject::class, $propertyName);

        $this->expectException(UndefinedProperty::class);
        $this->expectExceptionMessage($message);

        $this->object->$propertyName;
    }

    /**
     * @dataProvider propertyNamesProvider()
     */
    public function testMagicSet(string $propertyName, ?string $suggestion): void
    {
        $message = $suggestion !== null
            ? sprintf('Cannot write to an undeclared property %s::$%s, did you mean $%s?', SomeObject::class, $propertyName, $suggestion)
            : sprintf('Cannot write to an undeclared property %s::$%s.', SomeObject::class, $propertyName);

        $this->expectException(UndefinedProperty::class);
        $this->expectExceptionMessage($message);

        $this->object->$propertyName = 'value';
    }

    /**
     * @dataProvider propertyNamesProvider()
     */
    public function testMagicIsset(string $propertyName): void
    {
        self::assertFalse($this->object->__isset($propertyName));
        self::assertFalse(isset($this->object->$propertyName));
    }

    /**
     * @dataProvider propertyNamesProvider()
     */
    public function testMagicUnset(string $propertyName): void
    {
        $this->expectException(UndefinedProperty::class);
        $this->expectExceptionMessage(sprintf('Cannot unset an undeclared property %s::$%s.', SomeObject::class, $propertyName));

        unset($this->object->$propertyName);
    }
}
