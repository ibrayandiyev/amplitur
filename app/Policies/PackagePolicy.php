<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
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
        return $user->canManagePackages();
    }

    public function see($user, ?Package $package = null)
    {
        return $user->canSeePackage($package);
    }

    public function update($user, ?Package $package = null)
    {
        return $user->canUpdatePackage($package);
    }

    public function delete($user, ?Package $package = null)
    {
        return $user->canDeletePackage($package);
    }
}
