<?php

namespace App\Policies;

use App\Models\Hotel;
use Illuminate\Auth\Access\HandlesAuthorization;

class HotelPolicy
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
        return $user->canManageHotel();
    }

    public function view($user)
    {
        return $user->canViewHotel();
    }

    public function update($user, ?Hotel $hotel = null)
    {
        return $user->canUpdateHotel($hotel);
    }

    public function delete($user, ?Hotel $hotel = null)
    {
        return $user->canDeleteHotel($hotel);
    }
}
