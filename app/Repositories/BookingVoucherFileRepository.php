<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BookingVoucherFile;
use App\Services\BookingVoucherUploadService;
use Illuminate\Database\Eloquent\Model;

class BookingVoucherFileRepository extends Repository
{
    /**
     * @var Booking
     */
    protected $booking;

    /**
     * @var BookingLogRepository
     */
    protected $logging;

    public function __construct(BookingVoucherFile $model)
    {
        $this->model = $model;
        $this->logging = app(BookingLogRepository::class);
    }

    /**
     * [setBooking description]
     *
     * @param   Booking                     $booking  [$booking description]
     *
     * @return  BookingVoucherFileRepository          [return description]
     */
    public function setBooking(Booking $booking): BookingVoucherFileRepository
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        if (!empty($this->booking)) {
            $attributes['booking_id'] = $this->booking->id;
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        if (!empty($attributes['voucher'])) {
            $resource = app(BookingVoucherUploadService::class)->upload($attributes['voucher'], $resource);
        }

        $this->logging->bookingVoucherFileUploaded($resource->booking, $resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterDelete(Model $resource): Model
    {
        $this->logging->bookingVoucherFileDeleted($resource->booking, $resource);

        return $resource;
    }
}
