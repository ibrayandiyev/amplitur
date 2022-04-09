<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Promocode;
use App\Models\PromocodeGroup;
use App\Repositories\CurrencyRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\PromocodeGroupRepository;
use App\Repositories\PromocodeRepository;
use Exception;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    /**
     * @var PromocodeRepository
     */
    protected $repository;

    /**
     * @var PromocodeGroupRepository
     */
    protected $promocodeGroupRepository;

    /**
     * @var PackageRepository
     */
    protected $packageRepository;

    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    public function __construct(
        PromocodeRepository $repository,
        PromocodeGroupRepository $promocodeGroupRepository,
        PackageRepository $packageRepository,
        CurrencyRepository $currencyRepository,
        PaymentMethodRepository $paymentMethodRepository
        )
    {
        $this->repository = $repository;
        $this->promocodeGroupRepository = $promocodeGroupRepository;
        $this->packageRepository = $packageRepository;
        $this->currencyRepository = $currencyRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Promocode::class);

        try {
            $promocodeGroups = $this->promocodeGroupRepository->list();

            return view('backend.promocodes.index')
                ->with('promocodeGroups', $promocodeGroups);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * [create description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function create(Request $request, PromocodeGroup $promocodeGroup)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $currencies = $this->currencyRepository->list();

            $paymentMethods['national'] = $this->paymentMethodRepository->filter([
                'category' => 'national',
            ]);

            $paymentMethods['international'] = $this->paymentMethodRepository->filter([
                'category' => 'international',
            ]);

            return view('backend.promocodes.create')
                ->with('currencies', $currencies)
                ->with('paymentMethods', $paymentMethods)
                ->with('promocodeGroup', $promocodeGroup);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store the resource in storage.
     *
     * @param  Request      $request
     * @param  Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PromocodeGroup $promocodeGroup)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $attributes = $request->toArray();

            if (isset($attributes['expires_at'])) {
                $attributes['expires_at'] = convertDate($attributes['expires_at']);
            }

            $promocode = $this->repository->setPromocodeGroup($promocodeGroup)->store($attributes);

            return redirect()->route('backend.promocodes.edit', [$promocodeGroup, $promocode])->withSuccess(__('resources.promocodes.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.create', $promocodeGroup)->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PromocodeGroup  $promocodeGroup
     * @param  Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PromocodeGroup $promocodeGroup, Promocode $promocode)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $currencies = $this->currencyRepository->list();

            $paymentMethods['national'] = $this->paymentMethodRepository->filter([
                'category' => 'national',
            ]);

            $paymentMethods['international'] = $this->paymentMethodRepository->filter([
                'category' => 'international',
            ]);

            return view('backend.promocodes.edit')
                ->with('currencies', $currencies)
                ->with('promocodeGroup', $promocodeGroup)
                ->with('paymentMethods', $paymentMethods)
                ->with('promocode', $promocode);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request      $request
     * @param  PromocodeGroup  $promocodeGroup
     * @param  Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromocodeGroup $promocodeGroup, Promocode $promocode)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $attributes = $request->toArray();

            if (isset($attributes['expires_at'])) {
                $attributes['expires_at'] = convertDate($attributes['expires_at']);
            }

            $promocode = $this->repository->update($promocode, $attributes);

            return redirect()->route('backend.promocodes.edit', [$promocodeGroup, $promocode])->withSuccess(__('resources.promocodes.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.edit', [$promocodeGroup, $promocode])->withError($ex->getMessage());
        }
    }

    /**
     * [destroy description]
     *
     * @param   Request         $request         [$request description]
     * @param   PromocodeGroup  $promocodeGroup  [$promocodeGroup description]
     * @param   Promocode       $promocode       [$promocode description]
     *
     * @return  [type]                           [return description]
     */
    public function destroy(Request $request, PromocodeGroup $promocodeGroup, Promocode $promocode)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $promocode = $this->repository->delete($promocode);

            return redirect()->route('backend.promocodes.groups.edit', $promocodeGroup)->withSuccess(__('resources.promocodes.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.groups.edit', $promocodeGroup)->withError($ex->getMessage());
        }
    }

    /**
     * [create description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function createGroup(Request $request)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $packages = $this->packageRepository->list();

            return view('backend.promocodes.groups.create')
                ->with('packages', $packages);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store the resource in storage.
     *
     * @param  Request      $request
     * @param  Promocode  $promocode
     * @return \Illuminate\Http\Response
     */
    public function storeGroup(Request $request)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $attributes = $request->toArray();
            $promocodeGroup = $this->promocodeGroupRepository->store($attributes);

            return redirect()->route('backend.promocodes.groups.edit', $promocodeGroup)->withSuccess(__('resources.promocode-groups.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.groups.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PromocodeGroup  $promocodeGroup
     * @return \Illuminate\Http\Response
     */
    public function editGroup(Request $request, PromocodeGroup $promocodeGroup)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $packages = $this->packageRepository->list();

            return view('backend.promocodes.groups.edit')
                ->with('packages', $packages)
                ->with('promocodeGroup', $promocodeGroup);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request      $request
     * @param  PromocodeGroup  $promocodeGroup
     * @return \Illuminate\Http\Response
     */
    public function updateGroup(Request $request, PromocodeGroup $promocodeGroup)
    {
        $this->authorize('manage', Promocode::class);

        try {
            $attributes = $request->toArray();
            $promocodeGroup = $this->promocodeGroupRepository->update($promocodeGroup, $attributes);

            return redirect()->route('backend.promocodes.groups.edit', $promocodeGroup)->withSuccess(__('resources.promocode-groups.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.groups.edit', $promocodeGroup)->withError($ex->getMessage());
        }
    }

    /**
     * [destroy description]
     *
     * @param   Request         $request         [$request description]
     * @param   PromocodeGroup  $promocodeGroup  [$promocodeGroup description]
     * @param   Promocode       $promocode       [$promocode description]
     *
     * @return  [type]                           [return description]
     */
    public function destroyGroup(Request $request, PromocodeGroup $promocodeGroup)
    {
        $this->authorize('manage', Promocode::class);
    
        try {
            $promocode = $this->promocodeGroupRepository->delete($promocodeGroup);

            return redirect()->route('backend.promocodes.index')->withSuccess(__('resources.promocode-groups.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.promocodes.index', $promocode)->withError($ex->getMessage());
        }
    }
}
