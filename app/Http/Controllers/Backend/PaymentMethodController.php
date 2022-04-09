<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PaymentMethodTemplatesStoreRequest;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodTemplate;
use App\Repositories\PackageTemplateRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\PaymentMethodTemplateRepository;
use Exception;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * @var PaymentMethodRepository
     */
    protected $repository;

    /**
     * @var PaymentMethodTemplateRepository
     */
    protected $paymentMethodTemplateRepository;

    /**
     * @var PackageTemplateRepository
     */
    protected $packageTemplateRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    public function __construct(
        PaymentMethodRepository $repository,
        PaymentMethodTemplateRepository $paymentMethodTemplateRepository,
        PackageTemplateRepository $packageTemplateRepository,
        PaymentMethodRepository $paymentMethodRepository)
    {
        $this->repository               = $repository;
        $this->paymentMethodTemplateRepository = $paymentMethodTemplateRepository;
        $this->packageTemplateRepository = $packageTemplateRepository;
        $this->paymentMethodRepository  = $paymentMethodRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('manage', PaymentMethod::class);

        try {
            $paymentMethods['national'] = $this->repository->getNationals();
            $paymentMethods['international'] = $this->repository->getInternationals();

            $paymentMethodTemplates['national'] = $this->paymentMethodTemplateRepository->getNationals();
            $paymentMethodTemplates['international'] = $this->paymentMethodTemplateRepository->getInternationals();

            $packageTemplate        = $this->packageTemplateRepository->first();
            $billetPaymentMethods   = $this->paymentMethodRepository->getBilletPaymentMethods();

            $tab                            = $request->get('tab', '');

            return view('backend.paymentMethods.index')
                ->with('paymentMethods', $paymentMethods)
                ->with('packageTemplate', $packageTemplate)
                ->with('billetPaymentMethods', $billetPaymentMethods)
                ->with('tab', $tab)
                ->with('paymentMethodTemplates', $paymentMethodTemplates);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, PaymentMethod $paymentMethod)
    {
        $this->authorize('manage', PaymentMethod::class);

        try {
            $tab                            = $request->get('tab', '');

            return view('backend.paymentMethods.edit')
                ->with('tab', $tab)
                ->with('paymentMethod', $paymentMethod);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.paymentMethods.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request      $request
     * @param  PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $this->authorize('manage', PaymentMethod::class);

        try {
            $attributes = $request->toArray();
            $paymentMethod = $this->repository->update($paymentMethod, $attributes);

            return redirect()->route('backend.paymentMethods.edit', $paymentMethod)->withSuccess(__('resources.payment-methods.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.paymentMethods.edit', $paymentMethod)->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTemplate()
    {
        $this->authorize('manage', PaymentMethod::class);

        try {
            $paymentMethods = $this->repository->list();
            return view('backend.paymentMethods.createTemplate', compact('paymentMethods'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.paymentMethods.index', ['tab' => "template"])->withError($ex->getMessage());
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PaymentMethodTemplatesStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTemplate(PaymentMethodTemplatesStoreRequest $request)
    {
        $this->authorize('manage', PaymentMethod::class);

        try {
            $attributes = $request->toArray();

            $entity = $this->paymentMethodTemplateRepository->store($attributes);

            return redirect()->route('backend.paymentMethods.index', ['tab' => "template"])->withSuccess(__('resources.payment-methods.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.paymentMethods.createTemplate', ['tab' => "template"])->withError($ex->getMessage());
        }
    }

    /**
     * [updateTemplate description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function updateTemplate(Request $request)
    {
        $this->authorize('manage', PaymentMethod::class);

        try {
            $attributes = $request->toArray();

            $this->paymentMethodTemplateRepository->massUpdate($attributes['payment_method_templates']);
            $this->packageTemplateRepository->update($this->packageTemplateRepository->first(), $attributes['package_template']);

            return redirect()->route('backend.paymentMethods.index', ['tab' => "template"])->withSuccess(__('resources.payment-methods.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.paymentMethods.index', ['tab' => "template"])->withError($ex->getMessage());
        }
    }

    /**
     * [deleteTemplate description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function destroyTemplate(PaymentMethodTemplate $paymentMethodTemplate)
    {
        $this->authorize('manage', PaymentMethod::class);

        try {
            $this->paymentMethodTemplateRepository->delete($paymentMethodTemplate);

            return redirect()->route('backend.paymentMethods.index', ['tab' => "template"])->withSuccess(__('resources.payment-methods.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.paymentMethods.index', ['tab' => "template"])->withError($ex->getMessage());
        }
    }
}
