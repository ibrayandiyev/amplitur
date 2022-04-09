<?php

namespace App\Enums\Bookings;

abstract class BookingPayments
{
    public const POSTPONE_PAYMENT   = 'active';
    public const POSTPONE_NONE      = 'none';
    public const POSTPONE_WAITING   = 'none';

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::POSTPONE_PAYMENT . ',';
        $string .= self::POSTPONE_NONE . ',';
        $string .= self::POSTPONE_WAITING;

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
