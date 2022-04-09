<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingClient extends Model
{
    use BelongsToBooking,
        BelongsToClient,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'client_id',
        'name',
        'company_name',
        'legal_name',
        'email',
        'phone',
        'type',
        'birthdate',
        'identity',
        'uf',
        'primary_document',
        'document',
        'passport',
        'registry',
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

    public function isForeigner(): bool
    {
        return $this->address_country != 'BR';
    }
}
