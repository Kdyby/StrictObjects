<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects\Exception;

use Kdyby\StrictObjects\Suggester;
use LogicException;

use function get_class;
use function sprintf;

final class UndefinedMethod extends LogicException implements MemberAccessException
{
    public static function instance(object $object, string $name): self
    {
        return new self(
            self::formatMessage(
                get_class($object),
                $name,
                'instance',
                Suggester::suggestInstanceMethod($object, $name)
            )
        );
    }

    public static function static(string $class, string $name): self
    {
        return new self(
            self::formatMessage(
                $class,
                $name,
                'static',
                Suggester::suggestStaticMethod($class, $name)
            )
        );
    }

    private static function formatMessage(string $class, string $name, string $type, ?string $hint): string
    {
        $message = sprintf(
            'Call to an undefined %s method %s::%s()',
            $type,
            $class,
            $name
        );

        if ($hint === null) {
            return sprintf('%s.', $message);
        }

        return sprintf(
            '%s, did you mean %s()?',
            $message,
            $hint
        );
    }
}
