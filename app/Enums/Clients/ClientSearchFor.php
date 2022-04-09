<?php

namespace App\Enums\Clients;

abstract class ClientSearchFor
{
    public const FIELD_CPF          = 'document';
    public const FIELD_EMAIL        = 'email';
    public const FIELD_LOGIN        = 'username';
    public const FIELD_NAME         = 'name';
    public const FIELD_PHONE        = 'phone';
    public const FIELD_PASSPORT     = 'passport';
    public const FIELD_IDENTITY     = 'identity';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::FIELD_CPF . ',';
        $string .= self::FIELD_EMAIL . ',';
        $string .= self::FIELD_LOGIN . ',';
        $string .= self::FIELD_IDENTITY . ',';
        $string .= self::FIELD_NAME . ',';
        $string .= self::FIELD_PHONE . ',';
        $string .= self::FIELD_PASSPORT;

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
