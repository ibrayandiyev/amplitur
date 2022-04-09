<?php

namespace App\Models;

use App\Models\Relationships\BelongsToClient;
use App\Models\Relationships\BelongsToEvent;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Model;

class Prebooking extends Model
{
    use BelongsToEvent,
        BelongsToClient,
        HasDateLabels;

    protected $fillable = [
        'client_id',
        'event_id',
        'name',
        'email',
        'city',
        'country',
        'passengers',
        'responsible',
        'phone',
    ];

    /**
     * [isFromClient description]
     *
     * @return  bool    [return description]
     */
    public function isFromClient(): bool
    {
        return !is_null($this->client_id);
    }

    /**
     * [getName description]
     *
     * @return  string  [return description]
     */
    public function getName(): ?string
    {
        if (!empty($this->client)) {
            return $this->client->name;
        }

        return $this->name;
    }

    /**
     * [getEmail description]
     *
     * @return  string  [return description]
     */
    public function getEmail(): ?string
    {
        if (!empty($this->client)) {
            return $this->client->email;
        }

        return $this->email;
    }
}
