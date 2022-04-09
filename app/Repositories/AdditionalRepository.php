<?php

namespace App\Repositories;

use App\Enums\OfferType;
use App\Models\Additional;
use App\Models\Package;
use App\Models\Provider;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AdditionalRepository extends Repository
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var Package
     */
    protected $package;

    /**
     * @var Provider
     */
    protected $provider;

    public function __construct(Additional $model)
    {
        $this->model = $model;
    }

    public function setType(string $type): AdditionalRepository
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set provider to the instance
     *
     * @param   Provider|int  $offer
     *
     * @return  AdditionalRepository
     */
    public function setProvider($provider): AdditionalRepository
    {
        if ($provider instanceof Provider) {
            $this->provider = $provider;
        } else {
            $this->provider = app(ProviderRepository::class)->find($provider);
        }

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeDelete(Model $resource): Model
    {
        if($resource->offer->bookings()->count()){
            throw new Exception("This additional have bookings and cannot be removed");
        }

        return $resource;
    }

    /** 
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['price'] = sanitizeMoney($attributes['price']);

        return $attributes;
    }

    /** 
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes['price'] = sanitizeMoney($attributes['price']);

        return $attributes;
    }

    /**
     * List filtred if a type is defined
     *
     * @param   int|null         $paginate
     *
     * @return  Collection
     */
    public function list(?int $paginate = null, array $_params = null): Collection
    {
        $list = parent::list($paginate);

        if (!empty($this->type)) {
            $list = $list->filter(function ($value) {
                return $value->type == $this->type || $value->type == OfferType::ALL;
            });
        }

        if (!empty($this->provider)) {
            $list = $list->where('provider_id', $this->provider->id);
        }

        if (!empty($this->offer)) {
            $list = $list->where('offer_id', $this->offer->id);
        }

        return $list;
    }
}