<?php

namespace App\Enums\Bookings;

abstract class BookingLogs
{
    public const LOG_LEVEL_CLIENT   = [2,16];

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::LOG_LEVEL_CLIENT . ',';

        return $string;
    }

    /**
     * [toArray description]
     *
     * @return  array   [return description]
     */
    public static function toArray(): array
    {
        return explode(',', self::toString());
    }
}
