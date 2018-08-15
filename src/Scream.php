<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use function get_called_class;
use function get_class;
use function method_exists;
use function sprintf;

trait Scream
{
    /**
     * Call to undefined method.
     *
     * @param string  $name method name
     * @param mixed[] $args arguments
     * @throws MemberAccessException
     */
    public function __call(string $name, array $args) : void
    {
        $class = method_exists($this, $name) ? 'parent' : get_class($this);
        $hint  = Suggester::suggestMethod(get_class($this), $name);
        throw new MemberAccessException(sprintf(
            'Call to undefined method %s::%s()%s',
            $class,
            $name,
            $hint !== null ? sprintf(', did you mean %s()?', $hint) : '.'
        ));
    }

    /**
     * Call to undefined static method.
     *
     * @param string  $name method name (in lower case!)
     * @param mixed[] $args arguments
     * @throws MemberAccessException
     */
    public static function __callStatic(string $name, array $args) : void
    {
        $class = get_called_class();
        $hint  = Suggester::suggestStaticFunction($class, $name);
        throw new MemberAccessException(sprintf(
            'Call to undefined static function %s::%s()%s',
            $class,
            $name,
            $hint !== null ? sprintf(', did you mean %s()?', $hint) : '.'
        ));
    }

    /**
     * Returns property value. Do not call directly.
     *
     * @param string $name property name
     * @throws MemberAccessException
     */
    public function __get(string $name) : void
    {
        $class = get_class($this);
        $hint  = Suggester::suggestProperty($class, $name);
        throw new MemberAccessException(sprintf(
            'Cannot read an undeclared property %s::$%s%s',
            $class,
            $name,
            $hint !== null ? sprintf(', did you mean $%s?', $hint) : '.'
        ));
    }

    /**
     * Sets value of a property. Do not call directly.
     *
     * @param string $name  property name
     * @param mixed  $value property value
     * @throws MemberAccessException
     */
    public function __set(string $name, $value) : void
    {
        $class = get_class($this);
        $hint  = Suggester::suggestProperty($class, $name);
        throw new MemberAccessException(sprintf(
            'Cannot write to an undeclared property %s::$%s%s',
            $class,
            $name,
            $hint !== null ? sprintf(', did you mean $%s?', $hint) : '.'
        ));
    }

    /**
     * Is property defined?
     *
     * @param string $name property name
     * @throws MemberAccessException
     */
    public function __isset(string $name) : void
    {
        $class = get_class($this);
        $hint  = Suggester::suggestProperty($class, $name);
        throw new MemberAccessException(sprintf(
            'Cannot read an undeclared property %s::$%s%s',
            $class,
            $name,
            $hint !== null ? sprintf(', did you mean $%s?', $hint) : '.'
        ));
    }

    /**
     * Access to undeclared property.
     *
     * @param string $name property name
     * @throws MemberAccessException
     */
    public function __unset(string $name) : void
    {
        $class = get_class($this);
        throw new MemberAccessException(sprintf('Cannot unset the property %s::$%s.', $class, $name));
    }
}
