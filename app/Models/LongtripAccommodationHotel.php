<?php

namespace App\Models;

use App\Models\Relationships\BelongsToHotel;
use App\Models\Relationships\BelongsToLongtripAccommodation;
use App\Models\Relationships\BelongsToLongtripHotelLabel;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongtripAccommodationHotel extends Model
{
    use BelongsToLongtripAccommodation,
        BelongsToLongtripHotelLabel,
        BelongsToHotel,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'longtrip_accommodation_id',
        'longtrip_hotel_label_id',
        'hotel_id',
        'name',
        'address',
        'number',
        'neighborhood',
        'complement',
        'city',
        'state',
        'zip',
        'country',
        'latitude',
        'longitude',
        'checkin',
        'checkout',
    ];

    protected $casts = [
        'checkin' => 'datetime',
        'checkout' => 'datetime',
    ];

    /**
     * [hasStock description]
     *
     * @param   int   $quantity  [$quantity description]
     *
     * @return  bool             [return description]
     */
    public function hasStock($date, int $quantity = 1): bool
    {
        return $this->getPricing($date)->stock >= $quantity;
    }

    /**
     * [putStock description]
     *
     * @param   [type]$date      [$date description]
     * @param   int  $quantity  [$quantity description]
     *
     * @return  [type]          [return description]
     */
    public function putStock($date, int $quantity = 1)
    {
        $this->getPricing($date)->stock += $quantity;

        return $this->getPricing($date)->save();
    }

    /**
     * [pickStock description]
     *
     * @param   [type]$date      [$date description]
     * @param   int  $quantity  [$quantity description]
     *
     * @return  [type]          [return description]
     */
    public function pickStock($date, int $quantity = 1)
    {
        $this->getPricing($date)->stock -= $quantity;

        return $this->getPricing($date)->save();
    }

    /**
     * [getTypeLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getTypeLabelAttribute(): ?string
    {
        return $this->type->name;
    }

    /**
     * [getTitle description]
     *
     * @return  string  [return description]
     */
    public function getTitle(): ?string
    {
        return $this->getTypeLabelAttribute();
    }

    /**
     * [getInclusions description]
     *
     * @return  [type]  [return description]
     */
    public function getInclusions()
    {
        return $this->longtripRoute->inclusions;
    }

    /**
     * [getAdditionals description]
     *
     * @return  [type]  [return description]
     */
    public function getAdditionals()
    {
        return $this->longtripRoute->additionals;
    }

    /**
     * [getObservations description]
     *
     * @return  [type]  [return description]
     */
    public function getObservations()
    {
        return $this->longtripRoute->observations;
    }

    /**
     * [getFriendlyCheckin description]
     *
     * @return  [type]  [return description]
     */
    public function getFriendlyCheckin(): ?string
    {
        if (!empty($this->checkin)) {
            return $this->checkin->format('d/m/Y');
        }

        return null;
    }

    /**
     * [getFriendlyCheckout description]
     *
     * @return  [type]  [return description]
     */
    public function getFriendlyCheckout(): ?string
    {
        if (!empty($this->checkout)) {
            return $this->checkout->format('d/m/Y');
        }

        return null;
    }

    /**
     * [getHotelName description]
     *
     * @return  string  [return description]
     */
    public function getHotelName(): ?string
    {
        return $this->name;
    }

    /**
     * [getMapMarkUrl description]
     *
     * @return  string  [return description]
     */
    public function getMapMarkUrl(): ?string
    {
        return 'https://www.amplitur.com.br/imagens/icones/map_ico_hotel.png';
    }

    /**
     * [getLatitude description]
     *
     * @return  mixed   [return description]
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * [getLongitude description]
     *
     * @return  mixed   [return description]
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * [hasImages description]
     *
     * @return  bool    [return description]
     */
    public function hasImages(): bool
    {
        return !empty($this->images);
    }

    /**
     * [getStockStatusClass description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getStockStatusClass($date)
    {
        if ($this->isOutOfStock($date)) {
            return 'esgotado';
        }

        if ($this->isRunningOut($date)) {
            return 'esgotando';
        };

        return 'disponivel';
    }

    /**
     * [getLabelName description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getLabelName()
    {
        $labelName = $this->longtripAccommodation->type->name;
        if($this->longtrip_hotel_label_id){
            $labelName = $this->longtripHotelLabel->name;
        }
        return $labelName;
    }
    

    /**
     * [getStock description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getStock($date)
    {
        $pricing = $this->getPricing($date);

        return $pricing->stock ?? 0;
    }

    /**
     * [isOutOfStock description]
     *
     * @return  [type]  [return description]
     */
    public function isOutOfStock($date)
    {
        return $this->getStock($date) == 0;
    }

    /**
     * [isRunningOut description]
     *
     * @return  [type]  [return description]
     */
    public function isRunningOut($date)
    {
        return $this->getStock($date) <= 4;
    }

    /**
     * [isOneAvailable description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function isOneAvailable($date)
    {
        return $this->getStock($date) == 1;
    }

    /**
     * [getStockLabel description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getStockLabel($date)
    {
        if ($this->isOutOfStock($date)) {
            return (__('frontend.reservas.esgotado'));
        }

        if ($this->isOneAvailable($date)) {
            return (__('frontend.reservas.ultimas_unit'));
        }

        if ($this->isRunningOut($date)) {
            return "{$this->getStock($date)} ÚLTIMAS UNIDADES";
        }
    }

    /**
     * [days description]
     *
     * @return  [type]  [return description]
     */
    public function days()
    {
        if (empty($this->checkin) || empty($this->checkout)) {
            return 1;
        }

        $days = $this->checkin->startOfDay()->diffInDays($this->checkout->endOfDay());

        return $days;
    }
}
