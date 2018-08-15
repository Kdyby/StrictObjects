<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

trait Scream
{
    use PropertyScreamer;
    use MethodCallScreamer;
}
