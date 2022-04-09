<?php

namespace App\Enums;

abstract class CategoryType
{
    public const EVENT = 'event';
    public const PACKAGE = 'package';
    public const HOTEL = 'hotel';
    public const OTHER = 'other';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::EVENT . ',';
        $string .= self::PACKAGE . ',';
        $string .= self::HOTEL . ',';
        $string .= self::OTHER;

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
