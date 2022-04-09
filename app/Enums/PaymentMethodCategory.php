<?php

namespace App\Enums;

abstract class PaymentMethodCategory
{
    public const PM_CATEGORY_NATIONAL       = 'national';
    public const PM_CATEGORY_INTERNATIONAL  = 'international';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::PM_CATEGORY_NATIONAL . ',';
        $string .= self::PM_CATEGORY_INTERNATIONAL;

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
