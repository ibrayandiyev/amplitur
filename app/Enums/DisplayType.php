<?php

namespace App\Enums;

abstract class DisplayType
{
    public const PUBLIC     = 'public';
    public const EXCLUSIVE  = 'exclusive';
    public const NON_LISTED = 'non-listed';
    public const OUT = 'out';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::PUBLIC . ',';
        $string .= self::EXCLUSIVE . ',';
        $string .= self::OUT . ',';
        $string .= self::NON_LISTED;

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


