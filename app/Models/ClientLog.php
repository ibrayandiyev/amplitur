<?php

namespace App\Models;

use App\Models\Relationships\BelongsToProvider;
use App\Models\Relationships\BelongsToUser;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLog extends Model
{
    use BelongsToUser,
        BelongsToProvider,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'target_client_id',
        'user_id',
        'provider_id',
        'type',
        'level',
        'ip',
        'message',
    ];

    /**
     * [targetClient description]
     *
     * @return  [type]  [return description]
     */
    public function targetClient()
    {
        return $this->belongsTo(Client::class, 'target_client_id');
    }

    /**
     * [getOriginAttributes description]
     *
     * @return  [type]  [return description]
     */
    public function getOriginLabelAttribute(): string
    {
        if ($this->type == 'system') {
            return '<span class="label label-light-inverse">' . __('resources.logs.system') . '</span>';
        }

        if (!empty($this->user)) {
            return '<span class="label label-light-primary">' . $this->user->name . '</span>';
        }

        if (!empty($this->provider)) {
            return '<span class="label label-light-primary">' . $this->provider->name . '</span>';
        }

        return '<span class="label label-light-primary">' . __('resources.logs.system') . '</span>';
    }

    /**
     * [getLevelLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getLevelLabelAttribute(): string
    {
        return '<span class="label label-light-inverse">' . __('resources.logs.levels.' . $this->level) . '</span>';
    }
}
