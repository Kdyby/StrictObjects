Kdyby/StrictObjects
===================

Simple set of traits to make your classes strict, when calling or accessing an undefined member (property or method).

[![Build Status](https://github.com/Kdyby/StrictObjects/actions/workflows/tests.yaml/badge.svg)](https://github.com/Kdyby/StrictObjects/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/kdyby/strict-objects.svg)](https://packagist.org/packages/kdyby/strict-objects)
[![Latest stable](https://img.shields.io/packagist/v/kdyby/strict-objects.svg)](https://packagist.org/packages/kdyby/strict-objects)
[![Coverage Status](https://coveralls.io/repos/github/Kdyby/StrictObjects/badge.svg?branch=master)](https://coveralls.io/github/Kdyby/StrictObjects?branch=master)

This library is heavily inspired by [`Nette\ObjectMixin`](https://github.com/nette/utils/blob/e8749e5417bf22b0bd999d4b49ee799a5bad5fb9/src/Utils/ObjectMixin.php).

Installation
------------

The best way to install Kdyby/StrictObjects is using [Composer](https://getcomposer.org/):

```bash
composer require kdyby/strict-objects
```

Usage
-----

Simply include the trait in your class and it will behave strictly!

```php
use Kdyby\StrictObjects\Scream;

class MyClass
{
    use Scream;

    // my code
}
```

If you for some reason can't or don't want to make all undefined property and method access strict,
you can enable the behavior selectively by using specific traits:

 * `Kdyby\StrictObjects\PropertyReadScreamer`: Will throw when attempting to read an undeclared property.
 * `Kdyby\StrictObjects\PropertyWriteScreamer`: Will throw when attempting to write to an undeclared property.
 * `Kdyby\StrictObjects\PropertyExistsIgnorer`: Will always return false when attempting to check existence of an undeclared property.
 * `Kdyby\StrictObjects\PropertyRemovalScreamer`: Will throw when attempting to unset an undeclared property.
 * `Kdyby\StrictObjects\InstanceMethodCallScreamer`: Will throw when attempting to call an undefined instance method.
 * `Kdyby\StrictObjects\StaticMethodCallScreamer`: Will throw when attempting to call an undefined static method.

For convenience, there are also:

 * `Kdyby\StrictObjects\PropertyScreamer`: Combines all property access, will throw on any interaction.
 * `Kdyby\StrictObjects\MethodCallScreamer`: Combines both method call types, will throw for all methods.
 * `Kdyby\StrictObjects\Scream` combines all screamers into one.


-----

Homepage [https://www.kdyby.org](https://www.kdyby.org) and repository [https://github.com/Kdyby/StrictObjects](https://github.com/Kdyby/StrictObjects).
