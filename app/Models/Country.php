<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasTranslations;

    protected $fillable = [
        'id',
        'name',
        'iso3',
        'iso2',
        'phone_code',
        'capital',
        'currency',
        'native',
        'emoji',
        'emojiU',
    ];

    protected $translatable = [
        'name',
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }
}
