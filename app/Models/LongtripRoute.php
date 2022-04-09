<?php

namespace App\Models;

use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\HasManyLongtripAccommodations;
use App\Models\Relationships\HasManyLongtripAccommodationsPricing;
use App\Models\Relationships\HasManyLongtripBoardingLocations;
use App\Models\Relationships\MorphManyAdditionals;
use App\Models\Relationships\MorphManyExclusions;
use App\Models\Relationships\MorphManyInclusions;
use App\Models\Relationships\MorphManyObservations;
use App\Models\Traits\HasFieldsSaleDates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LongtripRoute extends Model
{
    use HasFactory,
        BelongsToOffer,
        HasManyLongtripBoardingLocations,
        HasManyLongtripAccommodations,
        HasManyLongtripAccommodationsPricing,
        MorphManyInclusions,
        MorphManyExclusions,
        MorphManyObservations,
        MorphManyAdditionals,
        HasFieldsSaleDates,
        HasTranslations;

    protected $fillable = [
        'offer_id',
        'name',
        'label_name',
        'capacity',
        'extra_observations',
        'extra_additionals',
        'extra_inclusions',
        'extra_exclusions',
        'fields',
    ];

    public $translatable = [
        'extra_observations',
        'extra_additionals',
        'extra_inclusions',
        'extra_exclusions',
        'label_name'
    ];

    public $casts = [
        'fields' => 'array',
    ];

    /**
     * Get all board locations formatted as list
     *
     * @return  string
     */
    public function getBoardingLocationsListAttribute()
    {
        $boardingLocations = $this->longtripBoardingLocations()->get();
        $list = '';

        foreach ($boardingLocations as $boardingLocation) {
            $list .= '<li>' . $boardingLocation->boardingAtLabel . ' <span class="fa fa-clock-o"></span> ' . $boardingLocation->boardingAtTimeLabel . ' - ' . city($boardingLocation->address->city) . ' - ' . state($boardingLocation->address->contry, $boardingLocation->address->state) . '</li>';
        }

        return $list;
    }

    /**
     * Get all board locations formatted as list
     *
     * @return  string
     */
    public function getBoardingLocationsEndsListAttribute()
    {
        $boardingLocations = $this->longtripBoardingLocations()->get();
        $list = '';

        foreach ($boardingLocations as $boardingLocation) {
            $list .= '<li>' . $boardingLocation->endsAtLabel . ' <span class="fa fa-clock-o"></span> ' . $boardingLocation->endsAtTimeLabel . '</li>';
        }

        return $list;
    }

    /**
     * [getBoardingLocationListLineAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getBoardingLocationListLineAttribute($separator = ", ")
    {
        if($separator == null){ $separator = ", ";}
        $boardingLocations = $this->longtripBoardingLocations()->get();
        $boardingLocationsArray = [];

        foreach ($boardingLocations as $boardingLocation) {
            $boardingInformation = city($boardingLocation->address->city);
            if($boardingLocation->price >0 ){
                $price = money($boardingLocation->getPrice(), $boardingLocation->longtripRoute->offer->currency);
                $boardingInformation .= " (". $price .")";
            }
            $boardingLocationsArray[] = $boardingInformation;
        }

        $list = implode($separator, $boardingLocationsArray);

        return $list;
    }

    /**
     * [getBoardingLocationListLineAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getBoardingLocationListLineBRAttribute()
    {
        return $this->getBoardingLocationListLineAttribute("<br />");
    }

    /**
     * [getLongtripAccommodationsTypesAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getLongtripAccommodationsTypesAttribute()
    {
        $longtripAccommodations = $this->longtripAccommodations()->get();
        $list = collect();

        foreach ($longtripAccommodations as $longtripAccommodation) {
            $list->push($longtripAccommodation->type);
        }

        $list = $list->unique('id');

        return $list;
    }

    /**
     * [hasPricing description]
     *
     * @return  [type]  [return description]
     */
    public function hasPricing()
    {
        return $this->longtripAccommodationsPricings()->count() > 0;
    }

    /**
     * [getLongtripAccommodationsTypesAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getLongtripRouteDatesAttribute()
    {
        $formattedSalesDates = "";
        if(isset($this->fields['sale_dates'])){
            $_saleDates = [];
            foreach($this->fields['sale_dates'] as $saleDate){
                $date = Carbon::createFromFormat("Y-m-d", $saleDate);
                $_saleDates[]  = $date->format("d/m/Y");
            }
            $formattedSalesDates = implode(" | ", $_saleDates);
        }

        return $formattedSalesDates;
    }

    /**
     * [getLongtripRouteDays description]
     *
     * @return  [type]  [return description]
     */
    public function getLongtripRouteDays()
    {
        $days = 0;
        if(isset($this->fields['sale_dates'])){
            $days = count($this->fields['sale_dates']);
        }

        return $days;
    }

    /**
     * [getBoardingLocationInitialDates description]
     *
     * @return  [type]  [return description]
     */
    public function getBoardingLocationInitialDates()
    {
        $_dates = null;
        if($this->longtripBoardingLocations){
            foreach($this->longtripBoardingLocations as $longtripBoardingLocation){
                $_dates[] = $longtripBoardingLocation->boarding_at->format("d/m/Y");
            }
        }
        return $_dates;
    }    
}
