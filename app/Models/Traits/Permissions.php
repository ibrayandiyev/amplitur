<?php

namespace App\Models\Traits;

use App\Models\Company;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Provider;

trait Permissions
{

    /**
     * [canSeeCompanyDocument description]
     *
     * @return  bool    [return description]
     */
    public function canSeeCompanyDocument($document): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin()
        || ($this->isProvider() && $document->provider_id == $this->id);
    }
    /**
     * [canManageClients description]
     *
     * @return  bool    [return description]
     */
    public function canManageClients(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageCompanies description]
     *
     * @return  bool    [return description]
     */
    public function canManageCompanies(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageProviders description]
     *
     * @return  bool    [return description]
     */
    public function canManageProviders(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canViewProviders description]
     *
     * @return  bool    [return description]
     */
    public function canViewProviders(): bool
    {
        return $this->isProvider();
    }

    /**
     * [canManagePrebookings description]
     *
     * @return  bool    [return description]
     */
    public function canManagePrebookings(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManagePromocodes description]
     *
     * @return  bool    [return description]
     */
    public function canManagePromocodes(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageFinancial description]
     *
     * @return  bool    [return description]
     */
    public function canManageFinancial(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageSystemSettings description]
     *
     * @return  bool    [return description]
     */
    public function canManageSystemSettings(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageEvents description]
     *
     * @return  bool    [return description]
     */
    public function canManageEvents(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageHotel description]
     *
     * @return  bool    [return description]
     */
    public function canManageHotel(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider());
    }

    /**
     * [canUpdateHotel description]
     *
     * @return  bool    [return description]
     */
    public function canUpdateHotel($hotel): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider() && $hotel->provider_id == $this->id);
    }

    /**
     * [canDeleteHotel description]
     *
     * @return  bool    [return description]
     */
    public function canDeleteHotel($hotel): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider() && $hotel->provider_id == $this->id);
    }

    /**
     * [canViewHotel description]
     *
     * @return  bool    [return description]
     */
    public function canViewHotel(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider());
    }

        /**
     * [canManageHotelDetails description]
     *
     * @return  bool    [return description]
     */
    public function canManageHotelDetails(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageCategories description]
     *
     * @return  bool    [return description]
     */
    public function canManageCategories(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageProviderDetails description]
     *
     * @return  bool    [return description]
     */
    public function canManageProviderDetails(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageSaleCoefficients description]
     *
     * @return  bool    [return description]
     */
    public function canManageSaleCoefficients(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageImages description]
     *
     * @return  bool    [return description]
     */
    public function canManageImages(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageImages description]
     *
     * @return  bool    [return description]
     */
    public function canMananageOfferImages(Offer $offer): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider() && $offer->provider_id == $this->id);
    }

    /**
     * [canManagePaymentMethods description]
     *
     * @return  bool    [return description]
     */
    public function canManagePaymentMethods(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageCurrencies description]
     *
     * @return  bool    [return description]
     */
    public function canManageCurrencies(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageStaticPages description]
     *
     * @return  bool    [return description]
     */
    public function canManageStaticPages(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageSlideshow description]
     *
     * @return  bool    [return description]
     */
    public function canManageSlideshow(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageUsers description]
     *
     * @return  bool    [return description]
     */
    public function canManageUsers(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }
    
    /**
     * [canSeePackageSeo description]
     *
     * @return  bool    [return description]
     */
    public function canSeePackageSeo(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canSeePackageOffers description]
     *
     * @return  bool    [return description]
     */
    public function canSeePackageOffers(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canSeePackagePaymentMethods description]
     *
     * @return  bool    [return description]
     */
    public function canSeePackagePaymentMethods(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManagePackages description]
     *
     * @return  bool    [return description]
     */
    public function canManagePackages(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || $this->isProvider();
    }

    /**
     * [canUpdatePackage description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  bool               [return description]
     */
    public function canSeePackage(?Package $package = null): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || $this->isProvider();
    }

    /**
     * [canUpdatePackage description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  bool               [return description]
     */
    public function canUpdatePackage(?Package $package = null): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canDeletePackage description]
     *
     * @return  bool    [return description]
     */
    public function canDeletePackage(?Package $package = null): bool
    {
        if (empty($package)) {
            return $this->isMaster() || $this->isManager() || $this->isAdmin();
        }

        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider() && $package->provider_id == $this->id);
    }

    /**
     * [canManagePackageDetails description]
     *
     * @return  bool    [return description]
     */
    public function canManagePackageDetails(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canProviderManagePackage description]
     *
     * @return  bool    [return description]
     */
    public function canProviderManagePackage(?Package $package = null, ?Provider $provider = null): bool
    {
        if($package && $provider && $package->provider_id == $provider->id)
        {
            return true;
        }
        return false;
    }

    /**
     * [canManageOffers description]
     *
     * @return  bool    [return description]
     */
    public function canManageOffers(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || $this->isProvider();
    }

    /**
     * [canUpdateOffer description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  bool           [return description]
     */
    public function canUpdateOffer(?Offer $offer): bool
    {
        if (empty($offer)) {
            return $this->isMaster() || $this->isManager() || $this->isAdmin();
        }

        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider() && $offer->provider_id == $this->id);
    }

    /**
     * [canDeleteOffer description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  bool           [return description]
     */
    public function canDeleteOffer(?Offer $offer): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canReplicateOffer description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  bool           [return description]
     */
    public function canReplicateOffer(Offer $offer): bool
    {
        if (empty($offer)) {
            return $this->isMaster() || $this->isManager() || $this->isAdmin();
        }

        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider() && $offer->provider_id == $this->id);
    }

    /**
     * [canManageOfferImages description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  bool           [return description]
     */
    public function canManageOfferImages(Offer $offer): bool
    {
        if (empty($offer)) {
            return $this->isMaster() || $this->isManager() || $this->isAdmin();
        }

        return $this->isMaster() || $this->isManager() || $this->isAdmin() || ($this->isProvider() && $offer->provider_id == $this->id);
    }

    /**
     * [canManageOfferSaleCoefficient description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  bool           [return description]
     */
    public function canManageOfferSaleCoefficient(?Offer $offer = null): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageOfferExtras description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  bool           [return description]
     */
    public function canManageOfferExtras(?Offer $offer = null): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageOfferCurrency description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  bool           [return description]
     */
    public function canManageOfferCurrency(?Offer $offer = null): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canCreateProviderCompanyOffer description]
     *
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     *
     * @return  bool                 [return description]
     */
    public function canCreateProviderCompanyOffer(Provider $provider, Company $company): bool
    {
        if ($this->isMaster() || $this->isManager() || $this->isAdmin()) {
            return true;
        }

        return ($this->isProvider() && $provider->id == $this->id && $company->provider_id = $this->id);
    }

    /**
     * [canManageNewsletters description]
     *
     * @return  bool    [return description]
     */
    public function canManageNewsletters(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canSeeBookingReport description]
     *
     * @return  bool    [return description]
     */
    public function canSeeBookingReport(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canSeeMasterReport description]
     *
     * @return  bool    [return description]
     */
    public function canSeeMasterReport(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageBookingDetails description]
     *
     * @return  bool    [return description]
     */
    public function canManageBookingDetails(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canRefundBookingPayment description]
     *
     * @return  bool    [return description]
     */
    public function canRefundBookingPayment(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canCancelBookingPayment description]
     *
     * @return  bool    [return description]
     */
    public function canCancelBooking(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canCancelBookingBillPayment description]
     *
     * @return  bool    [return description]
     */
    public function canCancelBookingBillPayment(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canSeeBookingContract description]
     *
     * @return  bool    [return description]
     */
    public function canSeeBookingContract(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canSeeBookingConfirmation description]
     *
     * @return  bool    [return description]
     */
    public function canSeeBookingConfirmation(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin() || $this->isProvider();
    }

    /**
     * [canSeeBookingConfirmation description]
     *
     * @return  bool    [return description]
     */
    public function canSeeBookingConfirmationPayments(): bool
    {
        return $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canSeeIpAddresses description]
     *
     * @return  bool    [return description]
     */
    public function canSeeIpAddresses(): bool
    {
        return  $this->isMaster() || $this->isManager() || $this->isAdmin();   
    }

    /**
     * [canManageClientLogs description]
     *
     * @return  bool    [return description]
     */
    public function canManageClientLogs(): bool
    {
        return  $this->isMaster() || $this->isManager() || $this->isAdmin() || $this->isProvider();   
    }

    /**
     * [canDeleteClientLog description]
     *
     * @return  bool    [return description]
     */
    public function canDeleteClientLog(): bool
    {
        return  $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageProviderLogs description]
     *
     * @return  bool    [return description]
     */
    public function canManageProviderLogs(): bool
    {
        return  $this->isMaster() || $this->isManager() || $this->isAdmin() || $this->isProvider();   
    }

    /**
     * [canDeleteProviderLog description]
     *
     * @return  bool    [return description]
     */
    public function canDeleteProviderLog(): bool
    {
        return  $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canManageProviderLogs description]
     *
     * @return  bool    [return description]
     */
    public function canManageBookingLogs(): bool
    {
        return  $this->isMaster() || $this->isManager() || $this->isAdmin() || $this->isProvider();   
    }

    /**
     * [canDeleteProviderLog description]
     *
     * @return  bool    [return description]
     */
    public function canDeleteBookingLog(): bool
    {
        return  $this->isMaster() || $this->isManager() || $this->isAdmin();
    }

    /**
     * [canOnlyProvider description]
     *
     * @return  bool    [return description]
     */
    public function canOnlyProvider(): bool
    {
        return  !($this->isMaster() && $this->isManager() && $this->isAdmin()) && $this->isProvider();
    }
}