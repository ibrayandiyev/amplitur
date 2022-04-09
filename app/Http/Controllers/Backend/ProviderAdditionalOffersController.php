<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Offers\ProviderAdditionalOfferItemStoreRequest;
use App\Http\Requests\Backend\Offers\ProviderAdditionalOfferItemUpdateRequest;
use App\Models\Additional;
use App\Models\AdditionalGroup;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Provider;
use App\Repositories\CompanyRepository;
use App\Repositories\OfferRepository;
use App\Repositories\ProviderRepository;
use Exception;
use Illuminate\Http\Request;

class ProviderAdditionalOffersController extends Controller
{
    public function __construct(OfferRepository $offerRepository, 
        ProviderRepository $providerRepository,
        CompanyRepository $companyRepository    
    )
    {
        $this->offerRepository      = $offerRepository;
        $this->companyRepository    = $companyRepository;
        $this->providerRepository   = $providerRepository;
    }

    /**
     * [createItem description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function createItem(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            $companies = $this->companyRepository->list();
            $providers = $this->providerRepository->list();
            $additionalGroups = $this->offerRepository->getAdditionalGroups($offer);

            return view('backend.offers.types.additional.items.create')
            ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('additionalGroups', $additionalGroups)
                ->with('providers', $providers)
                ->with('companies', $companies)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withError($ex->getMessage());
        }
    }

    /**
     * [storeItem description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function storeItem(ProviderAdditionalOfferItemStoreRequest $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            $attributes = $request->toArray();

            $this->offerRepository->storeAdditionalItem($offer, $attributes);

            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withSuccess(__('resources.additionals.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.additional.createItem', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withInput($attributes)->withError($ex->getMessage());
        }
    }

    /**
     * [createItem description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function editItem(Request $request, Provider $provider, Company $company, Offer $offer, Additional $additional)
    {
        try {
            $providers = $this->providerRepository->list();
            $companies = $this->companyRepository->list();
            $additionalGroups = $this->offerRepository->getAdditionalGroups($offer);

            return view('backend.offers.types.additional.items.edit')
            ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('additionalItem', $additional)
                ->with('additionalGroups', $additionalGroups)
                ->with('providers', $providers)
                ->with('companies', $companies)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withError($ex->getMessage());
        }
    }

    /**
     * [updateItem description]
     *
     * @param   Request        $request   [$request description]
     * @param   Provider       $provider  [$provider description]
     * @param   Company        $company   [$company description]
     * @param   Offer          $offer     [$offer description]
     * @param   Additional     $additional     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function updateItem(ProviderAdditionalOfferItemUpdateRequest $request, Provider $provider, Company $company, Offer $offer, Additional $additional)
    {
        try {
            $attributes = $request->toArray();

            $canChange = checkChangeOfferAdditional($offer);
            $attributes['restrict_update']  = $canChange;

            $attributes['keep_dates'] = isset($attributes['redirect']) && $attributes['redirect'] == 'back';

            $this->offerRepository->updateAdditionalItem($offer, $additional, $attributes);

            return redirect()->route('backend.providers.companies.offers.edit', ["provider" => $provider, "company" => $company, "offer" => $offer, "tab" => "item"])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ["provider" => $provider, "company" => $company, "offer" => $offer])->withInput($attributes)->withError($ex->getMessage());
        }
    }
    /**
     * [destroyItem description]
     *
     * @param   Request        $request   [$request description]
     * @param   Provider       $provider  [$provider description]
     * @param   Company        $company   [$company description]
     * @param   Offer          $offer     [$offer description]
     * @param   Additional     $additional     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function destroyItem(Request $request, Provider $provider, Company $company, Offer $offer, Additional $additional)
    {
        try {
            $additional = $this->offerRepository->destroyAdditional($offer, $additional);

            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withSuccess(__('messages.item_exclude_success'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withError($ex->getMessage());
        }
    }

    /**
     * [createGroup description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function createGroup(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            return view('backend.offers.types.additional.groups.create')
            ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withError($ex->getMessage());
        }
    }

    /**
     * [storeGroup description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function storeGroup(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            $attributes = $request->toArray();

            $this->offerRepository->storeAdditionalGroup($offer, $attributes);

            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withSuccess(__('resources.additionals.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.additional.createGroup', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withInput($attributes)->withError($ex->getMessage());
        }
    }

    /**
     * [editGroup description]
     *
     * @param   Request          $request          [$request description]
     * @param   Provider         $provider         [$provider description]
     * @param   Company          $company          [$company description]
     * @param   Offer            $offer            [$offer description]
     * @param   AdditionalGroup  $additionalGroup  [$additionalGroup description]
     *
     * @return  [type]                             [return description]
     */
    public function editGroup(Request $request, Provider $provider, Company $company, Offer $offer, AdditionalGroup $additionalGroup)
    {
        try {
            $providers = $this->providerRepository->list();

            return view('backend.offers.types.additional.groups.edit')
            ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('additionalGroup', $additionalGroup)
                ->with('providers', $providers);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withError($ex->getMessage());
        }
    }

    /**
     * [updateGroup description]
     *
     * @param   Request          $request          [$request description]
     * @param   Provider         $provider         [$provider description]
     * @param   Company          $company          [$company description]
     * @param   Offer            $offer            [$offer description]
     * @param   AdditionalGroup  $additionalGroup  [$additionalGroup description]
     *
     * @return  [type]                             [return description]
     */
    public function updateGroup(Request $request, Provider $provider, Company $company, Offer $offer, AdditionalGroup $additionalGroup)
    {
        try {
            $attributes = $request->toArray();

            $this->offerRepository->updateAdditionalGroup($offer, $additionalGroup, $attributes);

            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withInput($attributes)->withError($ex->getMessage());
        }
    }
    /**
     * [updateGroup description]
     *
     * @param   Request          $request          [$request description]
     * @param   Provider         $provider         [$provider description]
     * @param   Company          $company          [$company description]
     * @param   Offer            $offer            [$offer description]
     * @param   AdditionalGroup  $additionalGroup  [$additionalGroup description]
     *
     * @return  [type]                             [return description]
     */
    public function destroyGroup(Request $request, Provider $provider, Company $company, Offer $offer, AdditionalGroup $additionalGroup)
    {
        try {

            $this->offerRepository->destroyAdditionalGroup($offer, $additionalGroup);

            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => 'itens'])->withError($ex->getMessage());
        }
    }
}
