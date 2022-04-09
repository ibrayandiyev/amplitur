<?php

namespace App\Enums;

abstract class SelectionType
{
    public const MULTIPLE = 'multiple';
    public const SINGLE = 'single';
    
    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::MULTIPLE . ',';
        $string .= self::SINGLE;

        return $string;
    }
}
