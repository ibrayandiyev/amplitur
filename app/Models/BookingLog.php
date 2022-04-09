<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToProvider;
use App\Models\Relationships\BelongsToUser;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BookingLog extends Model
{
    use BelongsToUser,
        BelongsToProvider,
        BelongsToBooking,
        HasDateLabels,
        HasTranslations,
        HasFactory;

    protected $fillable = [
        'target_client_id',
        'target_booking_id',
        'user_id',
        'provider_id',
        'type',
        'level',
        'message',
        'operation',
        'ip',
        'created_at',
        'updated_at'
    ];

    protected $translatable = [
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
     * [targetBooking description]
     *
     * @return  [type]  [return description]
     */
    public function targetBooking()
    {
        return $this->belongsTo(Booking::class, 'target_booking_id');
    }

    /**
     * [getMessage description]
     *
     * @return  [type]  [return description]
     */
    public function getMessage()
    {
        return $this->message;
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
