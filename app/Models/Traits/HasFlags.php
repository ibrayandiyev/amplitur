<?php

namespace App\Models\Traits;

trait HasFlags
{
    /**
     * Get flag value
     *
     * @param   string  $flag
     * @param   mixed   $default
     *
     * @return  mixed
     */
    public function getFlag(string $flag, $default = null)
    {
        return $this->flags[$flag] ?? $default;
    }
}