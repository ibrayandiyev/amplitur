<?php

namespace App\Enums\Bookings;

abstract class BookingLogsOperation
{
    public const BOOKING_LOG_OPERATION_REFUND_STOCK             = 'refund_stock';
    public const BOOKING_LOG_OPERATION_BOOKING_CANCELLATION     = 'booking_cancellation';

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::BOOKING_LOG_OPERATION_REFUND_STOCK . ',';

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
