<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\OfferRepository;
use App\Repositories\PackageRepository;
use Exception;

class HomeController extends Controller
{
    /**
     * @var PackageRepository
     */
    protected $packageRepository;

    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    public function __construct(
        PackageRepository $packageRepository,
        OfferRepository $offerRepository
        )
    {
        $this->packageRepository = $packageRepository;
        $this->offerRepository = $offerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $packages = $this->packageRepository->setActor(user())->listTop10();
            $offers = $this->offerRepository->setActor(user())->listTop10();

            return view('backend.index')
                ->with('packages', $packages)
                ->with('offers', $offers);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            abort(500, $ex->getMessage());
        }
    }
}
