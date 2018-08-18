<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedProperty;

trait PropertyWriteScreamer
{
    /**
     * @param mixed $value
     *
     * @throws UndefinedProperty
     */
    public function __set(string $name, $value) : void
    {
        throw UndefinedProperty::write($this, $name);
    }
}
