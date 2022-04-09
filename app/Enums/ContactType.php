<?php

namespace App\Enums;

abstract class ContactType
{
    public const MOBILE = 'mobile';
    public const RESIDENTIAL = 'residential';
    public const COMMERCIAL = 'commercial';
    public const WHATSAPP = 'whatsapp';
    public const FAX = 'fax';
    public const FINANCIAL_EMAIL = 'financial-email';
    public const BOOKING_EMAIL = 'booking-email';
    public const FINANCIAL_PHONE = 'financial-phone';
    public const BOOKING_PHONE = 'booking-phone';
    public const OTHER = 'other';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::MOBILE . ',';
        $string .= self::RESIDENTIAL . ',';
        $string .= self::COMMERCIAL . ',';
        $string .= self::WHATSAPP . ',';
        $string .= self::FINANCIAL_EMAIL . ',';
        $string .= self::BOOKING_EMAIL . ',';
        $string .= self::FINANCIAL_PHONE . ',';
        $string .= self::BOOKING_PHONE . ',';
        $string .= self::FAX;

        return $string;
    }

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function toArray(): array
    {
        return explode(',', self::toString());
    }
}
