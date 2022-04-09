<?php

namespace App\Models\Relationships;

use App\Models\Promocode;

trait BelongsToPromocode
{
    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }

    public function promocodeProvider()
    {
        return $this->belongsTo(Promocode::class, "promocode_provider_id");
    }
}