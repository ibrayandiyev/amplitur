<?php

namespace App\Policies;

use App\Models\ProviderLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderLogPolicy
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
        return $user->canManageProviderLogs();
    }

    public function delete($user)
    {
        return $user->canDeleteProviderLog();
    }
}
