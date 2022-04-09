<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class InvoiceInformationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function manage($user)
    {
        return $user->canManageProviderDetails();
    }
}
