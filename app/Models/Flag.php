<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'value',
    ];

    public static function isImportingEvents(): bool
    {
        $flag = self::where('name', 'IS_IMPORTING_EVENTS')->first();
        
        if (is_null($flag)) {
            return false;
        }

        return (bool) $flag->value;
    }

    public static function failedImportingEvents(): bool
    {
        $flag = self::where('name', 'FAILED_IMPORTING_EVENTS')->first();
        
        if (is_null($flag)) {
            return false;
        }

        return (bool) $flag->value;
    }
}
