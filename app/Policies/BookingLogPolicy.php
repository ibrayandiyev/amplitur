<?php

namespace App\Policies;

use App\Models\BookingLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingLogPolicy
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
        return $user->canManageBookingLogs();
    }

    public function delete($user)
    {
        return $user->canDeleteBookingLog();
    }
}
