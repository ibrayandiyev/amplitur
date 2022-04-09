<?php

namespace App\Models\Relationships;

use App\Models\Client;

trait BelongsToClient
{
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}