<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Collection;

class CityRepository extends Repository
{
    public function __construct(City $model)
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
            if (!cache('cities')) {
                cache()->rememberForever('cities', function () {
                    return $this->model->orderBy('name')->get([
                        'id',
                        'state_code',
                        'country_code',
                        'name',
                    ]);
                });
            }

            return cache('cities');
        }
    }

    /**
     * [states description]
     *
     * @param   string      $countryCode
     * @param   string      $stateCode
     *
     * @return  Collection
     */
    public function getByCountryStateCode(string $countryCode, string $stateCode): Collection
    {
        $state = State::where('country_code', $countryCode)
            ->where('iso2', $stateCode)
            ->first();
        
        if (!$state) {
            return new Collection();
        }

        return $state->cities()->orderBy('name')->get([
            'id',
            'state_code',
            'country_code',
            'name',
        ]);
    }
}