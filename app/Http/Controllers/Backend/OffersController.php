<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Provider;
use App\Repositories\CompanyRepository;
use App\Repositories\OfferRepository;
use App\Repositories\PackageRepository;
use Exception;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    /**
     * @var OfferRepository
     */
    protected $repository;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    public function __construct(OfferRepository $repository,
        CompanyRepository $companyRepository,
        PackageRepository $packageRepository)
    {
        $this->repository           = $repository;
        $this->companyRepository    = $companyRepository;
        $this->packageRepository    = $packageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('manage', Offer::class);

        try {
            $params     = $request->toArray();

            $providers          = $companies = [];
            if(isset($params["package_id"])){
                $selectedPackage    = $this->packageRepository->find($params["package_id"]);
                $_providers         = $selectedPackage->offers()->pluck("provider_id")->toArray();
                $providers          = app(Provider::class)->whereIn("id", $_providers)->get();
                if(!isset($params['provider_id']) && count($_providers)){
                    $params['provider_id']  = $_providers[0];
                }
            }else{
            }
            if(isset($params["provider_id"])){
                $companies          = app(Company::class)->where("provider_id", $params["provider_id"])->get();
            }else{
            }

            $offers             = $this->repository->setActor(user())->filter($params);
            $packages           = $this->packageRepository->list();

            if(isset($params['analytic'])){
                $packages   = $this->packageRepository->setActor(user())
                    ->list(null, [
                        'analytic' => 1
                ]);
                $packageFilters = $packages;
                if(isset($params['package']) && $params['package'] != null){
                    $packages = $packages->where("id", "=", $params['package']);
                }
                return view('backend.offers.index_analytic', compact(['packages', 'packageFilters', 'params']));
            }
            return view('backend.offers.index', 
                compact(['offers']))
                ->with('_params', $params)
                ->with('packages', $packages)
                ->with('providers', $providers)
                ->with('companies', $companies)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * Show company selection to create
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', Offer::class);

        $companies = $this->companyRepository->setActor(user())->list();

        return view('backend.offers.company', compact('companies'));
    }

}
