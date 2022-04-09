<?php

namespace App\Enums;

abstract class DocumentType
{
    public const IDENTITY = 'identity';
    public const PASSPORT = 'passport';
    public const DOCUMENT = 'document';
    public const BIRTH_CERTIFICATE = 'birth-certificate';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::IDENTITY . ',';
        $string .= self::PASSPORT . ',';
        $string .= self::BIRTH_CERTIFICATE . ',';
        $string .= self::DOCUMENT;

        return $string;
    }
}
