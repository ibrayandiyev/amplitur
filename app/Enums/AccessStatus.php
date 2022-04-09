<?php

namespace App\Enums;

abstract class AccessStatus
{
    public const ACTIVE = 'active';
    public const SUSPENDED = 'suspended';
    public const BANNED = 'banned';
    public const PENDING = 'pending';

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::ACTIVE . ',';
        $string .= self::SUSPENDED . ',';
        $string .= self::BANNED . ',';
        $string .= self::PENDING;

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
