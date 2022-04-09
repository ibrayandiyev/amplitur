<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BookingVoucher;
use Illuminate\Database\Eloquent\Model;

class BookingVoucherRepository extends Repository
{
    /**
     * @var Booking
     */
    protected $booking;

    /**
     * @var BookingLogRepository
     */
    protected $logging;

    public function __construct(BookingVoucher $model)
    {
        $this->model = $model;
        $this->logging = app(BookingLogRepository::class);
    }

    /**
     * [setBooking description]
     *
     * @param   Booking                     $booking  [$booking description]
     *
     * @return  BookingVoucherRepository              [return description]
     */
    public function setBooking(Booking $booking): BookingVoucherRepository
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
        $this->logging->bookingVoucherCreated($resource->booking, $resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $this->logging->bookingVoucherUpdated($resource->booking, $resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterDelete(Model $resource): Model
    {
        $this->logging->bookingVoucherDeleted($resource->booking, $resource);

        return $resource;
    }
}
