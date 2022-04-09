<?php

namespace App\Enums;

abstract class UserType
{
    public const MASTER = 'master';
    public const ADMIN = 'admin';
    public const MANAGER = 'manager';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::MASTER . ',';
        $string .= self::ADMIN . ',';
        $string .= self::MANAGER;

        return $string;
    }
}
