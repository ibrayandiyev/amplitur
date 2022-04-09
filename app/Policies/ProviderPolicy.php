<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderPolicy
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

    public function ownerProviderPackage($user = null, $package = null)
    {
        return $user->canProviderManagePackage($package, $user);
    }

    public function onlyProvider($user)
    {
        return $user->canOnlyProvider();
    }

    public function manage($user)
    {
        return $user->canManageProviders();
    }

    public function view($user, $provider=null)
    {
        $check = $user->canViewProviders();
        if($check && $provider){
            $check = $provider->id == $user->id;
        }
        return $check;
    }

}
