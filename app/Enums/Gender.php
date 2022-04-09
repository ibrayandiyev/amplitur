<?php

namespace App\Enums;

abstract class Gender
{
    public const MALE = 'male';
    public const FEMALE = 'female';
    public const OTHER = 'other';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::MALE . ',';
        $string .= self::FEMALE . ',';
        $string .= self::OTHER;

        return $string;
    }
}