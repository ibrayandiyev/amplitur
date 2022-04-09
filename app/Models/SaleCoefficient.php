<?php

namespace App\Models;

use App\Models\Relationships\HasManyOffers;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCoefficient extends Model
{
    use HasManyOffers,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'name',
        'value',
        'is_default',
    ];

    /**
     * Check if coefficient is default
     *
     * @return  bool
     */
    public function isDefault(): bool
    {
        return $this->is_default == 1;
    }

    public function getExtendedNameAttribute(): ?string
    {
        return "{$this->name} ({$this->value})";
    }
}
