<?php

namespace App\Models;

use App\Models\Relationships\BelongsToCurrency;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class InvoiceInformation extends Model
{
    use HasTranslations,
        HasDateLabels,
        BelongsToCurrency,
        HasFactory;

    protected $fillable = [
        'description',
        'currency_id',
    ];

    protected $translatable = [
    ];

}
