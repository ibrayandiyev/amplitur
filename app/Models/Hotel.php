<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Relationships\BelongsToCategory;
use App\Models\Relationships\BelongsToManyHotelStructures;
use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\BelongsToProvider;
use App\Models\Relationships\HasManyHotelAccommodations;
use App\Models\Relationships\MorphManyObservations;
use App\Models\Relationships\MorphOneAddress;
use App\Models\Traits\HasProcessStatusLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Hotel extends BaseModel
{
    use MorphOneAddress,
        BelongsToOffer,
        BelongsToProvider,
        BelongsToCategory,
        HasFactory,
        BelongsToManyHotelStructures,
        HasProcessStatusLabels,
        MorphManyObservations,
        HasTranslations,
        SoftDeletes
        ;

    protected $with = [
        'address'
    ];

    protected $fillable = [
        'id',
        'provider_id',
        'category_id',
        'name',
        'registry_type',
        'checkin',
        'checkout',
        'images',
        'description',
        'extra_observations',
        'status',
        'changes',
        'created_at',
        'updated_at'
    ];
    
    protected $translatable = [
        'extra_observations',
    ];
}
