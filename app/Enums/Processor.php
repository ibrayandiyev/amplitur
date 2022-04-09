<?php

namespace App\Enums;

abstract class Processor
{
    public const SHOPLINE   = 'shopline';
    public const CIELO      = 'cielo';
    public const INTER      = 'inter';
    public const OFFLINE    = 'offline';
    public const PAYPAL     = 'paypal';
    public const REDE       = 'rede';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::SHOPLINE . ',';
        $string .= self::CIELO . ',';
        $string .= self::INTER . ',';
        $string .= self::OFFLINE . ',';
        $string .= self::PAYPAL . ',';
        $string .= self::REDE;

        return $string;
    }

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function toArray(): array
    {
        return explode(',', self::toString());
    }
}
