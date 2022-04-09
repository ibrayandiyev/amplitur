<?php

namespace App\Models;

use App\Enums\OfferType;
use App\Models\Relationships\BelongsToAdditionalGroup;
use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\BelongsToProvider;
use App\Models\Relationships\HasManyAdditionals;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AdditionalGroup extends Model
{
    use HasManyAdditionals,
        BelongsToProvider,
        BelongsToAdditionalGroup,
        BelongsToOffer,
        HasTranslations,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'provider_id',
        'additional_group_id',
        'offer_id',
        'internal_name',
        'name',
        'image',
        'selection_type',
    ];

    protected $translatable = [
        'name',
    ];

    protected $mapping = [
        OfferType::TICKET => 1,
        OfferType::AIRFARE => 2,
        OfferType::FOOD => 3,
        OfferType::TRAVEL_INSURANCE => 4,
        OfferType::TRANSFER => 5,
        OfferType::ADDITIONAL => 6,
    ];

    /**
     * [getOfferTypeMapped description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  int            [return description]
     */
    public function getOfferTypeMapped(Offer $offer): ?int
    {
        if (!isset($this->mapping[$offer->type])) {
            return null;
        }

        return $this->mapping[$offer->type];
    }

    /**
     * [isSingleSelection description]
     *
     * @return  bool    [return description]
     */
    public function isSingleSelection(): bool
    {
        return $this->selection_type == 'single';
    }
}
