<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\StateRepository;
use Exception;

class WorldController extends Controller
{
    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var StateRepository
     */
    protected $stateRepository;

    /**
     * @var CityRepository
     */
    protected $cityRepository;

    public function __construct(CountryRepository $countryRepository, StateRepository $stateRepository, CityRepository $cityRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->cityRepository = $cityRepository;
    }

    public function countries()
    {
        try {
            $countries = $this->countryRepository->list();

            return response($countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return response($ex);
        }
    }

    public function countryStates(string $countryCode)
    {
        try {
            $states = $this->countryRepository->states($countryCode);

            if (count($states) == 0) {
                return response('Not found', 404);
            }

            return response($states);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return response($ex);
        }
    }

    public function stateCities(string $countryCode, string $stateCode)
    {
        try {
            $cities = $this->stateRepository->cities($countryCode, $stateCode);

            if (count($cities) == 0) {
                return response('Not found', 404);
            }

            return response($cities);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return response($ex);
        }
    }
}
