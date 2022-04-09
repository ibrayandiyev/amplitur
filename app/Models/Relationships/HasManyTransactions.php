<?php

namespace App\Models\Relationships;

use App\Models\Transaction;

trait HasManyTransactions
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
