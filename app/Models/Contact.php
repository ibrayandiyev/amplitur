<?php

namespace App\Models;

use App\Models\Relationships\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use BelongsToClient,
        HasFactory;

    protected $fillable = [
        'contactable_id',
        'contactable_type',
        'name',
        'responsible',
        'value',
        'type',
        'is_primary',
    ];
}
