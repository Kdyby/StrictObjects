<?php

declare(strict_types=1);

namespace Kdyby\StrictObjects\Exception;

use Kdyby\StrictObjects\Suggester;
use LogicException;

use function sprintf;

final class UndefinedProperty extends LogicException implements Exception
{
    public static function read(object $object, string $name): self
    {
        return new self(self::formatMessageWithSuggestion($object, $name, 'read'));
    }

    public static function write(object $object, string $name): self
    {
        return new self(self::formatMessageWithSuggestion($object, $name, 'write to'));
    }

    public static function removal(object $object, string $name): self
    {
        return new self(self::formatMessageWithoutSuggestion($object, $name, 'unset'));
    }

    private static function formatMessageWithoutSuggestion(object $object, string $name, string $operation): string
    {
        return sprintf('%s.', self::formatMessage($object, $name, $operation));
    }

    private static function formatMessageWithSuggestion(object $object, string $name, string $operation): string
    {
        $suggestion = Suggester::suggestProperty($object, $name);

        if ($suggestion === null) {
            return self::formatMessageWithoutSuggestion($object, $name, $operation);
        }

        return sprintf(
            '%s, did you mean $%s?',
            self::formatMessage($object, $name, $operation),
            $suggestion
        );
    }

    private static function formatMessage(object $object, string $name, string $operation): string
    {
        return sprintf(
            'Cannot %s an undeclared property %s::$%s',
            $operation,
            $object::class,
            $name
        );
    }
}
