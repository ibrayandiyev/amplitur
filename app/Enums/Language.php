<?php

namespace App\Enums;

abstract class Language
{
    public const PORTUGUESE = 'pt-br';
    public const ENGLISH = 'en';
    public const SPANISH = 'es';

    public const LABELS  = ['pt-br' => "Portugues", 'en' => "Inglês", 'es' => "Espanhol"];

    /**
     * Returns enummerable attributes as string
     *
     * @return string
     */
    public static function toString(): string
    {
        $string = self::PORTUGUESE . ',';
        $string .= self::ENGLISH . ',';
        $string .= self::SPANISH;

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

    /**
     * [toArray description]
     *
     * @return  array   [return description]
     */
    public static function getLabel($language): string
    {
        return self::LABELS[$language] ?? null;
    }
}
