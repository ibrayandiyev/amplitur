<?php

namespace App\Repositories\Traits;

use App\Models\Hotel;
use App\Models\Offer;
use App\Repositories\AddressRepository;
use Illuminate\Database\Eloquent\Model;

trait HasAddress
{
    /**
     * Process resource adress
     *
     * @param   Model  $resource
     * @param   array  $attributes
     *
     * @return  void
     */
    public function handleAddress(Model $resource, array $attributes): void
    {
        $repository = app(AddressRepository::class);

        if (isset($resource->address->id)) {
            $repository->update($resource->address, $attributes);
        } else {
            $attributes['addressable_id'] = $resource->id;
            $attributes['addressable_type'] = get_class($resource);
            $repository->store($attributes);
        }
        
        if (isset($attributes['country']) && !($resource instanceof Offer) && !($resource instanceof Hotel)) {
            $resource->country = $attributes['country'];
        }
        
        $resource->save();
    }
}