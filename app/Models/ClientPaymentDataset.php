<?php

namespace App\Models;

use App\Enums\Processor;
use App\Models\Relationships\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPaymentDataset extends Model
{
    use BelongsToClient,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'client_id',
        'processor',
        'payload',
    ];

    /**
     * [isCielo description]
     *
     * @return  bool    [return description]
     */
    public function isCielo(): bool
    {
        return $this->processor == Processor::CIELO;
    }

    /**
     * [isCielo description]
     *
     * @return  bool    [return description]
     */
    public function isPaypal(): bool
    {
        return $this->processor == Processor::PAYPAL;
    }

    public function isShopline(): bool
    {
        return $this->processor == Processor::SHOPLINE;
    }

    public function isOffline(): bool
    {
        return $this->processor == Processor::OFFLINE;
    }
}
