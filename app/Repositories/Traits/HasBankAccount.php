<?php

namespace App\Repositories\Traits;

use App\Repositories\BankAccountRepository;
use Illuminate\Database\Eloquent\Model;

trait HasBankAccount
{
    /**
     * Handle currency association
     *
     * @param   Model  $resource
     * @param   array  $attributes
     *
     * @return  void
     */
    protected function handleBankAccount(Model $resource, array $attributes): void
    {
        $repository = app(BankAccountRepository::class);

        $currency = $attributes['currency'];
        $attributes = $attributes[$currency];
        $attributes['currency'] = $currency;

        if (isset($resource->bankAccount->id)) {
            $repository->update($resource->bankAccount, $attributes);
        } else {
            $attributes['provider_id'] = $resource->provider->id;
            $attributes['company_id'] = $resource->id;
            $repository->store($attributes);
        }
    }
}