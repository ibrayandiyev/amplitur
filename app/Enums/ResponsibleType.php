<?php

namespace App\Enums;

abstract class ResponsibleType
{
    public const CLIENT = 'client';
    public const AGENCY = 'agency';
    
    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::CLIENT . ',';
        $string .= self::AGENCY;

        return $string;
    }
}
