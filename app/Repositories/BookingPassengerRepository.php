<?php

namespace App\Repositories;

use App\Exceptions\NoStockException;
use App\Models\Booking;
use App\Models\BookingPassenger;
use Illuminate\Database\Eloquent\Model;

class BookingPassengerRepository extends Repository
{
    /**
     * @var Booking
     */
    protected $booking;

    /**
     * @var BookingProductRepository
     */
    protected $bookingProductRepository;

    /**
     * @var BookingLogRepository
     */
    protected $logging;

    public function __construct(BookingPassenger $model,
        BookingProductRepository $bookingProductRepository)
    {
        $this->model                    = $model;
        $this->logging                  = app(BookingLogRepository::class);
        $this->bookingProductRepository = $bookingProductRepository;

    }

    /**
     * [setBooking description]
     *
     * @param   Booking                     $booking  [$booking description]
     *
     * @return  BookingPassengerRepository            [return description]
     */
    public function setBooking(Booking $booking): BookingPassengerRepository
    {
        $this->booking = $booking;
        $this->logging = app(BookingLogRepository::class);

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
        if($resource->booking->bookingProducts){
            $bookingProducts = $resource->booking->bookingProducts;
            foreach($bookingProducts as $bookingProduct){
                if(!$this->bookingProductRepository->hasStock($bookingProduct, 1)){
                    throw new NoStockException(__('backend.booking.no_stock_product', ["product" => $bookingProduct->getTitle()]));
                }
                $this->bookingProductRepository->pickStock($bookingProduct, 1);
            }
        }
        $this->logging->passengerCreated($resource->booking, $resource);
        
        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $this->logging->passengerUpdated($resource->booking, $resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterDelete(Model $resource): Model
    {
        if($resource->booking->bookingProducts){
            $bookingProducts = $resource->booking->bookingProducts;
            foreach($bookingProducts as $bookingProduct){
                $this->bookingProductRepository->putStock($bookingProduct, 1);
            }
        }
        $this->logging->passengerDeleted($resource->booking, $resource);

        return $resource;
    }
}