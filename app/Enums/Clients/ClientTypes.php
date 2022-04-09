<?php

namespace App\Enums\Clients;

abstract class ClientTypes
{
    public const PERSON_TYPE_FISICAL    = 'fisical';
    public const PERSON_TYPE_LEGAL      = 'legal';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::PERSON_TYPE_LEGAL . ',';
        $string .= self::PERSON_TYPE_FISICAL;

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
