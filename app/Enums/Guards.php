<?php

namespace App\Enums;

abstract class Guards
{
    public const USERS      = 'users';
    public const PROVIDERS  = 'providers';
    public const CLIENTS    = 'clients';

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::USERS . ',';
        $string .= self::PROVIDERS . ',';
        $string .= self::CLIENTS;

        return $string;
    }

    /**
     * [toArray description]
     *
     * @return  array   [return description]
     */
    public static function toArray(): array
    {
        return explode(',', self::toString());
    }
}
