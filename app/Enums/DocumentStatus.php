<?php

namespace App\Enums;

abstract class DocumentStatus
{
    public const IN_ANALYSIS = 'in-analysis';
    public const APPROVED = 'approved';
    public const DECLINED = 'declined';

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::IN_ANALYSIS . ',';
        $string .= self::APPROVED . ',';
        $string .= self::DECLINED;

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
