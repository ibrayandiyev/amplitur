<?php

namespace App\Repositories;

use App\Enums\OfferType;
use App\Models\AdditionalGroup;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Collection;

class AdditionalGroupRepository extends Repository
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var Provider
     */
    protected $provider;

    public function __construct(AdditionalGroup $model)
    {
        $this->model = $model;
    }

    public function setType(string $type): AdditionalGroupRepository
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set provider to the instance
     *
     * @param   Provider|int  $offer
     *
     * @return  AdditionalGroupRepository
     */
    public function setProvider($provider): AdditionalGroupRepository
    {
        if ($provider instanceof Provider) {
            $this->provider = $provider;
        } else {
            $this->provider = $this->find($provider);
        }

        return $this;
    }

    /** 
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['type'] = $this->type;

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

        return $list;
    }

    /**
     * [getPublicAddtionalGroups description]
     *
     * @return  Collection[return description]
     */
    public function getPublicAddtionalGroups(): Collection
    {
        $groups = $this->model
            ->whereNull('provider_id')
            ->whereNull('offer_id')
            ->get();

        return $groups;
    }
}