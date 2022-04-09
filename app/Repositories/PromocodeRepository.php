<?php

namespace App\Repositories;

use App\Exceptions\Promocodes\PromocodeException;
use App\Models\Currency;
use App\Models\Promocode;
use App\Models\PromocodeGroup;
use Illuminate\Database\Eloquent\Model;

class PromocodeRepository extends Repository
{
    /**
     * @var PromocodeGroup
     */
    protected $promocodeGroup;

    public function __construct(Promocode $model)
    {
        $this->model = $model;
    }

    /**
     * [setPromocodeGroup description]
     *
     * @param   PromocodeGroup  $promocodeGroup  [$promocodeGroup description]
     *
     * @return  [type]                           [return description]
     */
    public function setPromocodeGroup(PromocodeGroup $promocodeGroup): PromocodeRepository
    {
        $this->promocodeGroup = $promocodeGroup;

        return $this;
    }

    /** 
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['promocode_group_id'] = $this->promocodeGroup->id;
        $attributes['discount_value'] = sanitizeMoney($attributes['discount_value']);
        $attributes['stock'] = empty($attributes['stock']) ? 0 : $attributes['stock'];
        $attributes['max_installments'] = empty($attributes['max_installments']) ? 0 : $attributes['max_installments'];

        return $attributes;
    }

    /** 
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes['discount_value'] = sanitizeMoney($attributes['discount_value']);
        $attributes['stock'] = empty($attributes['stock']) ? 0 : $attributes['stock'];
        $attributes['max_installments'] = empty($attributes['max_installments']) ? 0 : $attributes['max_installments'];

        return $attributes;
    }

    /**
     * [findByCode description]
     *
     * @param   string  $code       [$code description]
     * @param   int     $packageId  [$packageId description]
     *
     * @return  [type]              [return description]
     */
    public function findByCode(?string $code, ?int $packageId = null): ?Promocode
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * [getDiscount description]
     *
     * @param   Promocode  $promocode  [$promocode description]
     *
     * @return  [type]                 [return description]
     */
    public function getDiscount(?Promocode $promocode, Currency $currency)
    {
        if (!empty($promocode) && !empty($promocode->discount_value)) {
            $discount_value = moneyFloat($promocode->discount_value, $currency, $promocode->currency);
            return $discount_value;
        }

        return 0;
    }

    /**
     * [validatePromocode description]
     *
     * @param   Promocode  $promocode  [$promocode description]
     *
     * @return  [type]                 [return description]
     */
    public function validatePromocode(?string $code, ?int $packageId = null)
    {
        $promocode = $this
            ->findByCode($code, $packageId ?? null);
        if($promocode && !$promocode->isAvailable()){
            throw new PromocodeException("Promocode is not available.");
        }

        return $promocode;
    }
}