<?php

namespace App\Enums;

abstract class PersonType
{
    public const FISICAL = 'fisical';
    public const LEGAL = 'legal';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::FISICAL . ',';
        $string .= self::LEGAL;

        return $string;
    }
}
