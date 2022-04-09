<?php

namespace App\Enums;

abstract class Currency
{
    public const REAL   = 'BRL';
    public const EURO   = 'EUR';
    public const DOLLAR = 'USD';
    public const LIBRA  = 'GBP';

    public const CURRENCY_IDS   = ["BRL" => 1, "EUR" => 2, "GBP" => 3, "USD" => 4];

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::REAL . ',';
        $string .= self::EURO . ',';
        $string .= self::DOLLAR . ',';
        $string .= self::LIBRA;

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
