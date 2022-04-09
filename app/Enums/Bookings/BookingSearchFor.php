<?php

namespace App\Enums\Bookings;

abstract class BookingSearchFor
{
    public const FIELD_CPF          = 'booking_client_document';
    public const FIELD_EMAIL        = 'booking_client_email';
    public const FIELD_NAME         = 'booking_client_name';
    public const FIELD_PHONE        = 'booking_client_phone';
    public const FIELD_PASSPORT     = 'booking_client_passport';
    public const FIELD_IDENTITY     = 'booking_client_identity';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::FIELD_CPF . ',';
        $string .= self::FIELD_EMAIL . ',';
        $string .= self::FIELD_LOGIN . ',';
        $string .= self::FIELD_IDENTITY . ',';
        $string .= self::FIELD_NAME . ',';
        $string .= self::FIELD_PHONE . ',';
        $string .= self::FIELD_PASSPORT;

        return $string;
    }

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function toArray(): array
    {
        $array = explode(',', self::toString());

        return $array;
    }
}
