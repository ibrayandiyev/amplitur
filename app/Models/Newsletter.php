<?php

namespace App\Models;

use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasDateLabels,
        HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];
}
