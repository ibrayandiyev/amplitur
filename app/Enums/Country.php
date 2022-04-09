<?php

namespace App\Enums;

abstract class Country
{
    public const BRAZIL = 'BR';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::BRAZIL;

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
