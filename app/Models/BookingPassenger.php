<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\HasManyBookingPassengerAdditionals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    use BelongsToBooking,
        HasManyBookingPassengerAdditionals,
        HasFactory;

    protected $fillable = [
        'id',
        'booking_id',
        'name',
        'email',
        'phone',
        'birthdate',
        'primary_document',
        'identity',
        'uf',
        'document',
        'passport',
        'address',
        'address_number',
        'address_neighborhood',
        'address_complement',
        'address_city',
        'address_state',
        'address_zip',
        'address_country',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];
}
