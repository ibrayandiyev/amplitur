<?php

namespace App\Enums;

abstract class Hotel
{
    public const REGISTRY_TYPE_DEFAULT     = 'default';
    public const REGISTRY_TYPE_HOTEL       = 'hotel';
    public const REGISTRY_TYPE_LONGTRIP    = 'longtrip';


    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::REGISTRY_TYPE_DEFAULT . ',';
        $string .= self::REGISTRY_TYPE_HOTEL . ',';
        $string .= self::REGISTRY_TYPE_LONGTRIP;

        return $string;
    }

    public static function getOfferTypeTranslation($offerType='default'){
        $_offer_types = [
            self::REGISTRY_TYPE_DEFAULT => 'Default', 
            self::REGISTRY_TYPE_HOTEL => 'Hospedagem', 
            self::REGISTRY_TYPE_LONGTRIP => 'Longtrip', 
        ];
        return isset($_offer_types[$offerType])?$_offer_types[$offerType]:'Not found';
    }
}