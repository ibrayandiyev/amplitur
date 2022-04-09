<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,
        BelongsToBooking;

    protected $fillable = [
        'booking_id',
        'booking_bill_id',
        'payload',
        'status',
        'amount',
        'gateway',
        'encrypted',
        'operation'
    ];

    
}
