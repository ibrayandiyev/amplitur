<?php

namespace App\Repositories;

use App\Enums\AccessStatus;
use App\Models\User;
use App\Repositories\Traits\HasPassword;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository
{
    use HasPassword;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes = $this->handlePassword($attributes);
        $attributes['is_active'] = $attributes['status'] == AccessStatus::ACTIVE;

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes = $this->handlePassword($attributes);
        $attributes['is_active'] = $attributes['status'] == AccessStatus::ACTIVE;

        return $attributes;
    }
}