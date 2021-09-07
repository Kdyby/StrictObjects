<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedProperty;

trait PropertyWriteScreamer
{
    /**
     * @throws UndefinedProperty
     */
    public function __set(string $name, mixed $value): void
    {
        throw UndefinedProperty::write($this, $name);
    }
}
