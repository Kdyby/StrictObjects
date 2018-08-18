<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

trait MethodCallScreamer
{
    use InstanceMethodCallScreamer;
    use StaticMethodCallScreamer;
}
