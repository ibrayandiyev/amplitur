<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasProcessStatusLabels;

class BookingLegacies extends BaseModel
{
    use HasDateLabels,
    HasProcessStatusLabels
    ;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'booking_id',
        'client_id',
        'name',
        'starts_at',
        'status'
    ];

    protected $casts = [
        'starts_at' => 'datetime'
    ];

}
