<?php

namespace App\Models;

use App\Models\Relationships\BelongsToCompany;
use App\Models\Relationships\BelongsToProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use BelongsToProvider,
        BelongsToCompany,
        HasFactory;

    protected $fillable = [
        'provider_id',
        'company_id',
        'currency',
        'bank',
        'agency',
        'account_type',
        'account_number',
        'wire',
        'routing_number',
        'iban',
        'sort_code',
    ];
}
