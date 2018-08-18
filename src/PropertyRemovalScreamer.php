<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedProperty;

trait PropertyRemovalScreamer
{
    /**
     * @throws UndefinedProperty
     */
    public function __unset(string $name) : void
    {
        throw UndefinedProperty::removal($this, $name);
    }
}
