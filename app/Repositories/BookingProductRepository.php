<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BookingProduct;
use App\Models\BustripBoardingLocation;
use App\Models\HotelAccommodation;
use App\Models\ShuttleBoardingLocation;

class BookingProductRepository extends Repository
{
    protected $type;

    public function __construct(BookingProduct $model)
    {
        $this->model = $model;
    }

    /**
     * [putStock description]
     *
     * @param   BookingProduct  $bookingProduct  [$bookingProduct description]
     * @param   int             $quantity        [$quantity description]
     *
     * @return  [type]                           [return description]
     */
    public function putStock(BookingProduct $bookingProduct, int $quantity = 1)
    {
        $product    = $bookingProduct->getProduct();
        $date       = $bookingProduct->date->format('Y-m-d') ?? null;
        
        return $product->putStock($date, $quantity);
    }

    /**
     * [pick description]
     *
     * @param   BookingProduct  $bookingProduct  [$bookingProduct description]
     * @param   int             $quantity        [$quantity description]
     *
     * @return  [type]                           [return description]
     */
    public function pickStock(BookingProduct $bookingProduct, int $quantity = 1)
    {
        $product = $bookingProduct->getProduct();
        $date = $bookingProduct->date->format('Y-m-d') ?? null;

        return $product->pickStock($date, $quantity);
    }

    /**
     * [hasStock description]
     *
     * @param   BookingProduct  $bookingProduct  [$bookingProduct description]
     * @param   int             $quantity        [$quantity description]
     *
     * @return  [type]                           [return description]
     */
    public function hasStock(BookingProduct $bookingProduct, int $quantity = 1)
    {
        $product = $bookingProduct->getProduct();
        $date = $bookingProduct->date->format('Y-m-d') ?? null;

        return $product->hasStock($date, $quantity);
    }

    /**
     * [setBooking description]
     *
     * @param   Booking                     $booking  [$booking description]
     *
     * @return  BookingPassengerRepository            [return description]
     */
    public function setBooking(Booking $booking): BookingProductRepository
    {
        $this->booking = $booking;

        return $this;
    }
}