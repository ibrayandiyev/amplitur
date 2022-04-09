<?php

namespace App\Policies;

use App\Models\ClientLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientLogPolicy
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
        return $user->canManageClientLogs();
    }

    public function delete($user)
    {
        return $user->canDeleteClientLog();
    }
}
