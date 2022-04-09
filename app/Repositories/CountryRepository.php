<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

class CountryRepository extends Repository
{
    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    /**
     * @inherited
     */
    public function list(int $paginate = null, array $_params = null): Collection
    {
        if ($paginate) {
            return $this->model->orderByRaw('name COLLATE utf8_french_ci')->paginate($paginate);
        } else {
            if (!cache('countries')) {
                cache()->rememberForever('countries', function () {
                    return $this->model->orderBy('name')->get([
                        'id',
                        'iso2',
                        'currency',
                        'name',
                        'phonecode',
                    ]);
                });

                $this->makeTranslatableCacheArray();
            }

            return cache('countries');
        }
    }

    /**
     * [getAsKeyValue description]
     *
     * @return  [type]  [return description]
     */
    public function getAsKeyValue()
    {
        if (!cache('countries_array')) {
            $this->makeTranslatableCacheArray();
        }

        return cache('countries_array');
    }

    /**
     * [makeTranslatableCacheArray description]
     *
     * @return  [type]  [return description]
     */
    protected function makeTranslatableCacheArray()
    {
        cache()->rememberForever('countries_array', function () {
            if (!cache('countries')) {
                cache()->rememberForever('countries', function () {
                    return $this->model->orderBy('name')->get([
                        'id',
                        'iso2',
                        'currency',
                        'name',
                        'phonecode',
                    ]);
                });

                $this->makeTranslatableCacheArray();
            }
            $cache = cache('countries');
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

    /**
     * [states description]
     *
     * @param   string      $code  [$code description]
     *
     * @return  Collection         [return description]
     */
    public function states(string $code): Collection
    {
        $repository = app(StateRepository::class);

        $states = $repository->getByCountryCode($code);

        return $states;
    }
}