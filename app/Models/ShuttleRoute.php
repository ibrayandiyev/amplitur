<?php

namespace App\Models;

use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\HasManyShuttleBoardingLocations;
use App\Models\Relationships\MorphManyExclusions;
use App\Models\Relationships\MorphManyInclusions;
use App\Models\Relationships\MorphManyObservations;
use App\Models\Traits\HasFieldsSaleDates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ShuttleRoute extends Model
{
    use HasFactory,
        BelongsToOffer,
        HasManyShuttleBoardingLocations,
        MorphManyInclusions,
        MorphManyExclusions,
        MorphManyObservations,
        HasFieldsSaleDates,
        HasTranslations;

    protected $fillable = [
        'offer_id',
        'name',
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
        $boardingLocations = $this->shuttleBoardingLocations()->get();
        $list = '';

        foreach ($boardingLocations as $boardingLocation) {
            $list .= '<li>' . $boardingLocation->boardingAtLabel . ' <span class="fa fa-clock-o"></span> ' .
            $boardingLocation->boardingAtTimeLabel . ' - ' . $boardingLocation->address->complement  . ' - ' .
            city($boardingLocation->address->city) . ' - ' . state($boardingLocation->address->contry, $boardingLocation->address->state);
        }

        return $list;
    }

    /**
     * Get all sales date formatted
     *
     * @return  string
     */
    public function getSalesDatesFormattedAttribute()
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
}
