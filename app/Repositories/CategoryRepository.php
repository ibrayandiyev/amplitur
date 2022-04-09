<?php

namespace App\Repositories;

use App\Models\Category;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository extends Repository
{
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        if ($attributes['type'] != Category::TYPE_EVENT && isset($attributes['flags']['DURATION'])) {
            unset($attributes['flags']['DURATION']);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        if ($attributes['type'] != Category::TYPE_EVENT && isset($attributes['flags']['DURATION'])) {
            unset($attributes['flags']['DURATION']);
        }

        return $attributes;
    }

    /**
     * List all items of a resource filtring just event type
     *
     * @return  Collection
     */
    public function listEvent()
    {
        return $this->model->where('type', Category::TYPE_EVENT)->get();
    }

    public function listHotel()
    {
        return $this->model->where('type', Category::TYPE_HOTEL)->get();
    }

    /**
     * [getAsKeyValue description]
     *
     * @return  [type]  [return description]
     */
    public function getAsKeyValue()
    {
        if (!cache('categories_array')) {
            $this->makeTranslatableCacheArray();
        }

        return cache('categories_array');
    }

    /**
     * [makeTranslatableCacheArray description]
     *
     * @return  [type]  [return description]
     */
    protected function makeTranslatableCacheArray()
    {
        cache()->rememberForever('categories_array', function () {
            if (!cache('categories')) {
                cache()->rememberForever('categories', function () {
                    return $this->model->orderBy('name')->get([
                        'id',
                        'slug',
                        'description',
                        'name',
                        'type',
                    ]);
                });

                $this->makeTranslatableCacheArray();
            }
            $cache = cache('categories');
            return $cache->mapWithKeys(function ($item) {
                $country = [
                    $item->iso2 => [
                        'en' => $item->getTranslation('name', 'en'),
                        'pt-br' => $item->getTranslation('name', 'pt-br'),
                        'es' => $item->getTranslation('name', 'es'),
                        'phonecode' => $item->phonecode,
                    ],
                ];

                return $country;
            });
        });
    }
}