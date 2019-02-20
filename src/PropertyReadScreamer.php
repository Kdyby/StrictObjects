<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use Kdyby\StrictObjects\Exception\UndefinedProperty;

trait PropertyReadScreamer
{
    /**
     * @return mixed
     *
     * @throws UndefinedProperty
     */
    public function &__get(string $name)
    {
        throw UndefinedProperty::read($this, $name);
    }
}
