<?php

namespace App\Models;

use App\Models\Relationships\HasManyBookingProducts;
use App\Models\Relationships\HasManyBookings;
use App\Models\Relationships\HasManyCurrencyQuotations;
use App\Models\Relationships\HasManyCurrencyQuotationHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasManyCurrencyQuotations,
        HasManyCurrencyQuotationHistory,
        HasManyBookingProducts,
        HasManyBookings,
        HasFactory;

    protected $fillable = [
        'name',
        'code',
        'symbol',
    ];
}
