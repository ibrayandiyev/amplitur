<?php

namespace App\Models\Relationships;

use App\Models\BankAccount;

trait HasManyBankAccounts
{
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
