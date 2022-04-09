<?php

namespace App\Repositories;

use App\Exceptions\NoStockException;
use App\Models\Additional;
use App\Models\Booking;
use App\Models\BookingPassengerAdditional;
use Illuminate\Database\Eloquent\Model;

class BookingPassengerAdditionalRepository extends Repository
{
    /**
     * @var Booking
     */
    protected $booking;

    /**
     * @var BookingLogRepository
     */
    protected $logging;

    public function __construct(BookingPassengerAdditional $model)
    {
        $this->model = $model;
        $this->logging = app(BookingLogRepository::class);
    }

    /**
     * [setBooking description]
     *
     * @param   Booking                     $booking  [$booking description]
     *
     * @return  BookingPassengerRepository            [return description]
     */
    public function setBooking(Booking $booking): BookingPassengerAdditionalRepository
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
            $attributes['currency_id'] = $this->booking->currency_id;
        }

        $additional = Additional::where('id', $attributes['additional_id'])->first();

        if (!empty($additional) && !$additional->hasStock()) {
            throw new NoStockException;
        }
        $attributes['sale_coefficient']     = $additional->getSaleCoefficient();

        if(!isset($attributes['currency_origin_id'])){
            $currency                           = $additional->getCurrency();
            $attributes['currency_origin_id']   = ($currency)?$currency->id:null;
        }
        if(!isset($attributes['company_id'])){
            $attributes['company_id']           =  $additional->offer->company_id;
        }

        $attributes['price']    = $attributes['price_net'] = sanitizeMoney($attributes['price']);
        if($attributes['sale_coefficient']>0){
            $attributes['price_net']       = ($attributes['price'] / $attributes['sale_coefficient']);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes['price']        = sanitizeMoney($attributes['price']);
        $attributes['price_net']    = sanitizeMoney($attributes['price_net']);

        /* Removed this as asked by John. 16/10.
        if (!empty($resource->additional) && !$resource->additional->hasStock()) {
            $message    = "This '{$resource->additional->name}' does not have stock: {$resource->additional->stock}";
            throw new NoStockException($message);
        }
        */

        $this->operationAdditionalPutStock($resource);

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $this->logging->passengerAdditionalCreated($resource->booking, $resource);

        $resource->additional->pickStock();

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $this->logging->passengerAdditionalUpdated($resource->booking, $resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterDelete(Model $resource): Model
    {
        $this->operationAdditionalPutStock($resource);
        $this->logging->passengerAdditionalDeleted($resource->booking, $resource);

        return $resource;
    }

    /**
     * [operationAdditionalPutStock description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]                     [return description]
     */
    public function operationAdditionalPutStock(Model $resource){
        $resource->additional->putStock();
        $this->logging->passengerAdditionalDeleted($resource->booking, $resource);
    }
}