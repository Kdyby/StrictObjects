Kdyby/StrictObjects
======

Simple trait to make your class strict, when calling or accessing undefined member (property or method).

[![Build Status](https://travis-ci.org/Kdyby/StrictObjects.svg?branch=2.0)](https://travis-ci.org/Kdyby/StrictObjects)
[![Downloads this Month](https://img.shields.io/packagist/dm/kdyby/strict-objects.svg)](https://packagist.org/packages/kdyby/strict-objects)
[![Latest stable](https://img.shields.io/packagist/v/kdyby/strict-objects.svg)](https://packagist.org/packages/kdyby/strict-objects)
[![Coverage Status](https://coveralls.io/repos/github/Kdyby/StrictObjects/badge.svg?branch=master)](https://coveralls.io/github/Kdyby/StrictObjects?branch=master)

This library is heavily inspired by [Nette\ObjectMixin](https://github.com/nette/utils/blob/e8749e5417bf22b0bd999d4b49ee799a5bad5fb9/src/Utils/ObjectMixin.php).

Installation
------------

The best way to install Kdyby/StrictObjects is using  [Composer](http://getcomposer.org/):

```sh
$ composer require kdyby/strict-objects:^2.0
```

Usage
-----

Simply include the trait in your class and it will behave strictly!

```php
class MyClass
{
	use \Kdyby\StrictObjects\Scream;

	// my code
}
```

-----

Homepage [http://www.kdyby.org](http://www.kdyby.org) and repository [http://github.com/Kdyby/StrictObjects](http://github.com/Kdyby/StrictObjects).
