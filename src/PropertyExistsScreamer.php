<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedProperty;

trait PropertyExistsScreamer
{
    /**
     * @throws UndefinedProperty
     */
    public function __isset(string $name) : bool
    {
        throw UndefinedProperty::exists($this, $name);
    }
}
