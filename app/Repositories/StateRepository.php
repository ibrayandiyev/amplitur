<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Collection;

class StateRepository extends Repository
{
    public function __construct(State $model)
    {
        $this->model = $model;
    }

    /**
     * @inherited
     */
    public function list(int $paginate = null, array $_params = null): Collection
    {
        if ($paginate) {
            return $this->model->paginate($paginate);
        } else {
            if (!cache('states')) {
                cache()->rememberForever('states', function () {
                    return $this->model->orderBy('name')->get([
                        'id',
                        'country_code',
                        'iso2',
                        'name',
                    ]);
                });
            }

            return cache('states');
        }
    }

    /**
     * [states description]
     *
     * @param   string      $code  [$code description]
     *
     * @return  Collection         [return description]
     */
    public function getByCountryCode(string $code): Collection
    {
        $country = Country::where('iso2', $code)->first();
        
        if (!$country) {
            return new Collection();
        }

        return $country->states()->orderBy('name')->get([
            'id',
            'country_code',
            'iso2',
            'name',
        ]);
    }

    public function getAsKeyValue()
    {
        if (!cache('states_array')) {
            cache()->rememberForever('states_array', function () {
                $states = [];

                foreach ($this->list() as $state) {
                    $states[$state['country_code']][$state['iso2']] = $state['name'];
                }

                return $states;
            });
        }

        return cache('states_array');
    }

    /**
     * [cities description]
     *
     * @param   string      $countryCode
     * @param   string      $stateCode
     *
     * @return  Collection         [return description]
     */
    public function cities(string $countryCode, string $stateCode): Collection
    {
        $repository = app(CityRepository::class);

        $cities = $repository->getByCountryStateCode($countryCode, $stateCode);

        return $cities;
    }
}