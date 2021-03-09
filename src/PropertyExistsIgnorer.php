<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

trait PropertyExistsIgnorer
{
    public function __isset(string $name): bool
    {
        return false;
    }
}
