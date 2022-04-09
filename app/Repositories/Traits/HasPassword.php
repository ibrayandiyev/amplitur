<?php

namespace App\Repositories\Traits;

trait HasPassword
{
    /**
     * Handle password by crypting it
     *
     * @param   array  $attributes
     *
     * @return  array
     */
    protected function handlePassword(array $attributes): array
    {
        if (!isset($attributes['password'])) {
            unset($attributes['password']);
            return $attributes;
        }

        $attributes['password'] = bcrypt($attributes['password']);

        return $attributes;
    }
}