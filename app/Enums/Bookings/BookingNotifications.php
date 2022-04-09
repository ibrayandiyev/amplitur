<?php

namespace App\Enums\Bookings;

abstract class BookingNotifications
{
    public const NOTIFICATION_CLIENT    = 'client';
    public const NOTIFICATION_PROVIDER  = 'provider';

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::NOTIFICATION_CLIENT . ',';
        $string .= self::NOTIFICATION_PROVIDER;

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
