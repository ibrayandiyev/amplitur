<?php

namespace App\Enums;

use App\Models\BustripBoardingLocation;
use App\Models\HotelAccommodation;
use App\Models\LongtripAccommodationsPricing;
use App\Models\LongtripBoardingLocation;
use App\Models\ShuttleBoardingLocation;

abstract class OfferType
{
    public const ALL = 'all';
    public const HOTEL_ACCOMMODATION = 'hotel-accommodation';

    // Main Services
    public const BUSTRIP = 'bus-trip';
    public const SHUTTLE = 'shuttle';
    public const HOTEL = 'hotel';
    public const LONGTRIP = 'longtrip';
    public const LONGTRIP_BOARDING_LOCATION = 'longtrip_boarding_location';

    // Secondary Services
    public const TICKET = 'ticket';
    public const TRAVEL_INSURANCE = 'travel-insurance';
    public const FOOD = 'food';
    public const AIRFARE = 'airfare';
    public const TRANSFER = 'transfer';
    public const ADDITIONAL = 'additional';

    // Classes
    public const CLASS_TYPE_BUSTRIP     = BustripBoardingLocation::class;
    public const CLASS_TYPE_HOTEL       = HotelAccommodation::class;
    public const CLASS_TYPE_LONGTRIP    = LongtripAccommodationsPricing::class;
    public const CLASS_TYPE_SHUTTLE     = ShuttleBoardingLocation::class;

    public const CLASS_TYPE_NAMES       = [
        BustripBoardingLocation::class => "Bustrip",
        HotelAccommodation::class => "Hotel",
        LongtripAccommodationsPricing::class => "Longtrip",
        LongtripBoardingLocation::class => "Longtrip",
        ShuttleBoardingLocation::class => "Shuttle",
    ];

    public const CLASS_TYPE_OFFER_TYPE  = [
        BustripBoardingLocation::class => self::BUSTRIP,
        HotelAccommodation::class => self::HOTEL,
        LongtripAccommodationsPricing::class => self::LONGTRIP,
        LongtripBoardingLocation::class => self::LONGTRIP,
        ShuttleBoardingLocation::class => self::SHUTTLE,
    ];

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function toString(): string
    {
        $string = self::ALL . ',';
        $string .= self::HOTEL_ACCOMMODATION . ',';

        $string .= self::BUSTRIP . ',';
        $string .= self::SHUTTLE . ',';
        $string .= self::HOTEL . ',';
        $string .= self::LONGTRIP . ',';
        
        $string .= self::TICKET . ',';
        $string .= self::TRAVEL_INSURANCE . ',';
        $string .= self::FOOD . ',';
        $string .= self::AIRFARE . ',';
        $string .= self::TRANSFER . ',';
        $string .= self::ADDITIONAL;

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

    /**
     * Returns enummerable attributes as string
     * 
     * @return string
     */
    public static function classTypeToString(): string
    {
        $string = self::CLASS_TYPE_BUSTRIP . ',';
        $string .= self::CLASS_TYPE_HOTEL . ',';

        $string .= self::CLASS_TYPE_LONGTRIP . ',';
        $string .= self::CLASS_TYPE_SHUTTLE ;

        return $string;
    }

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function getClassTypeName($classType): string
    {
        if(isset(self::CLASS_TYPE_NAMES[$classType])){
            return self::CLASS_TYPE_NAMES[$classType];
        }
        return null;
    }

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function getClassTypeNameTranslation($classType): string
    {
        if(isset(self::CLASS_TYPE_NAMES[$classType])){
            return __("backend.booking.class_type.". strtolower(self::CLASS_TYPE_NAMES[$classType]));
        }
        return null;
    }

    /**
     * Returns enummerable attributes as array
     *
     * @return  array
     */
    public static function classTypeToArray(): array
    {
        return explode(',', self::classTypeToString());
    }
}
