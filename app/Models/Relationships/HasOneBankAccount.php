<?php

namespace App\Models\Relationships;

use App\Models\BankAccount;

trait HasOneBankAccount
{
    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }
}
