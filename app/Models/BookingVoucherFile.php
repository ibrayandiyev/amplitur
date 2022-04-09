<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingVoucherFile extends Model
{
    use BelongsToBooking,
        BelongsToUser,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'path',
        'filename',
        'title',
    ];

    /**
     * [getVoucherUrl description]
     *
     * @return  string  [return description]
     */
    public function getVoucherUrl(): ?string
    {
        return voucherFile($this->path . '/' . $this->filename);
    }
}
