<?php

namespace Kdyby\StrictObjects;

interface Exception
{

}

class MemberAccessException extends \LogicException implements \Kdyby\StrictObjects\Exception
{

}
