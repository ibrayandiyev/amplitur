<?php

namespace App\Repositories;

use App\Models\SaleCoefficient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class SaleCoefficientRepository extends Repository
{
    public function __construct(SaleCoefficient $model)
    {
        $this->model = $model;
    }

    public function listOrdered(): SupportCollection
    {
        $collection = DB::table($this->model->getTable())
            ->select(DB::raw('*, CAST(name as SIGNED) as bin_column'))
            ->orderBy('is_default', 'desc')
            ->orderBy('bin_column', 'asc')
            ->get();

        return $collection;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $this->handleDefaultAttribute($resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $this->handleDefaultAttribute($resource);

        return $resource;
    }

    /**
     * Get the default sale coefficient
     *
     * @return  SaleCoefficient[return description]
     */
    public function getDefaultCoefficient(): ?SaleCoefficient
    {
        $saleCoefficient = $this->model->where('is_default', true)->first();

        return $saleCoefficient;
    }

    /**
     * Switch coefficient defaultness
     *
     * @param   Model  $resource
     *
     * @return  void
     */
    protected function handleDefaultAttribute(Model $resource): void
    {
        if ($resource->isDefault()) {
            $this->model->where('is_default', 1)->update([
                'is_default' => false
            ]);

            $resource->is_default = true;
            $resource->save();
        }
    }
}