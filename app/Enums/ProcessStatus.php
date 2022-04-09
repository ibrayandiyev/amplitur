<?php

namespace App\Enums;

abstract class ProcessStatus
{
    public const IN_ANALYSIS        = 'in-analysis';
    public const PENDING            = 'pending';
    public const PENDING_CONFIRMATION    = 'pending_confirmation';
    public const ON_GOING           = 'on-going';
    public const ACTIVE             = 'active';
    public const CONFIRMED          = 'confirmed';
    public const COURTESY           = 'courtesy';
    public const RELEASED           = 'released';
    public const REFUSED            = 'refused';
    public const BLOCKED            = 'blocked';
    public const CANCELED           = 'canceled';
    public const OVERDUED           = 'overdued';
    public const SUSPENDED          = 'suspended';
    public const PAID               = 'paid';
    public const PARTIAL_PAID       = 'partial_paid';
    public const PARTIAL_RECEIVED   = 'partial_received';
    public const PRERESERVED        = 'prereserved';
    public const REFUNDED           = 'refunded';
    public const INACTIVE           = 'inactive';

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::IN_ANALYSIS . ',';
        $string .= self::PENDING . ',';
        $string .= self::ON_GOING . ',';
        $string .= self::ACTIVE . ',';
        $string .= self::RELEASED . ',';
        $string .= self::CONFIRMED . ',';
        $string .= self::COURTESY . ',';
        $string .= self::REFUSED . ',';
        $string .= self::BLOCKED . ',';
        $string .= self::CANCELED . ',';
        $string .= self::INACTIVE . ',';
        $string .= self::OVERDUED . ',';
        $string .= self::PAID     . ',';
        $string .= self::PARTIAL_PAID . ',';
        $string .= self::PARTIAL_RECEIVED . ',';
        $string .= self::PRERESERVED . ',';
        $string .= self::REFUNDED . ',';
        $string .= self::SUSPENDED;

        return $string;
    }

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toStringPaymentStatus(): string
    {
        $string = self::PENDING . ',';
        $string .= self::PAID ;

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

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function toArrayPaymentStatus(): array
    {
        return explode(',', self::toStringPaymentStatus());
    }
}
