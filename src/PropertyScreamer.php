<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

trait PropertyScreamer
{
    use PropertyReadScreamer;
    use PropertyWriteScreamer;
    use PropertyExistsScreamer;
    use PropertyRemovalScreamer;
}
