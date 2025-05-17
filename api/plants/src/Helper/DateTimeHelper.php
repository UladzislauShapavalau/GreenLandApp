<?php

declare(strict_types=1);

namespace App\Helper;

use App\Exception\DateTimeException;

final class DateTimeHelper
{
    /**
     * @param string $rawDate
     * @return \DateTimeImmutable
     * @throws DateTimeException
     */
    public static function fromString(string $rawDate): \DateTimeImmutable
    {
        try {
            return new \DateTimeImmutable($rawDate);
        } catch (\Exception $e) {
            throw new DateTimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param mixed $rawDate
     * @return \DateTimeImmutable
     * @throws DateTimeException
     */
    public static function create(mixed $rawDate): \DateTimeImmutable
    {
        return match (true) {
            is_string($rawDate) => self::fromString($rawDate),
            default => throw new DateTimeException('Invalid raw date'),
        };
    }

    /**
     * @return \DateTimeImmutable
     * @throws DateTimeException
     */
    public static function now(): \DateTimeImmutable
    {
        return self::create('now');
    }
}
