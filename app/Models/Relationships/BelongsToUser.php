<?php

namespace App\Models\Relationships;

use App\Models\User;

trait BelongsToUser
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}