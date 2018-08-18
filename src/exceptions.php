<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects;

use LogicException;

interface Exception
{
}

class MemberAccessException extends LogicException implements Exception
{
}
