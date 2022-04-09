<?php

namespace App\Providers;

use App\Events\Bookings\BookingCreatedEvent;
use App\Events\Bookings\BookingStatusEvent;
use App\Events\ClientCreatedEvent;
use App\Events\OfferCreatedEvent;
use App\Events\OfferDestroyEvent;
use App\Events\OfferUpdateDependenciesEvent;
use App\Events\OfferUpdatedEvent;
use App\Events\PackageCreatedEvent;
use App\Events\PackageUpdatedEvent;
use App\Events\Providers\CompanyCreatedEvent;
use App\Events\Providers\ProviderCreatedEvent;
use App\Events\Providers\ProviderUpdatedEvent;
use App\Listeners\Bookings\BookingProcessStatusChange;
use App\Listeners\Bookings\SendBookingNotification;
use App\Listeners\Clients\SendClientRegistryBackofficeNotification;
use App\Listeners\Clients\SendClientWelcomeNotification;
use App\Listeners\Offers\OfferSanitizeDelete;
use App\Listeners\Offers\OfferUpdatePackageDependenciesNotification;
use App\Listeners\Providers\SendCompanyRegistryBackofficeNotification;
use App\Listeners\Providers\SendCompanyWelcomeNotification;
use App\Listeners\SendOfferActiveNotification;
use App\Listeners\SendOfferInAnalysisNotification;
use App\Listeners\SendOfferRefusedNotification;
use App\Listeners\SendOfferSuspendedNotification;
use App\Listeners\SendPackageActiveNotification;
use App\Listeners\SendPackageInAnalysisNotification;
use App\Listeners\SendPackageRefusedNotification;
use App\Listeners\SendPackageSuspendedNotification;
use App\Listeners\Providers\SendProviderActiveNotification;
use App\Listeners\Providers\SendProviderInAnalysisNotification;
use App\Listeners\Providers\SendProviderRefusedNotification;
use App\Listeners\Providers\SendProviderRegistryBackofficeNotification;
use App\Listeners\Providers\SendProviderSuspendedNotification;
use App\Listeners\Providers\SendProviderWelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            // SendEmailVerificationNotification::class,
        ],

        // Client events
        ClientCreatedEvent::class => [
            SendClientWelcomeNotification::class,
            SendClientRegistryBackofficeNotification::class
        ],

        // Provider events
        ProviderCreatedEvent::class => [
            SendProviderWelcomeNotification::class,
            SendProviderRegistryBackofficeNotification::class
        ],
        // Compnay events
        CompanyCreatedEvent::class => [
            SendCompanyWelcomeNotification::class,
            SendCompanyRegistryBackofficeNotification::class
        ],

        ProviderUpdatedEvent::class => [
            // SendProviderInAnalysisNotification::class,
            // SendProviderActiveNotification::class,
            // SendProviderRefusedNotification::class,
            // SendProviderSuspendedNotification::class,
        ],

        // Offer events
        OfferCreatedEvent::class => [
            // SendOfferInAnalysisNotification::class,
        ],

        // Offer events
        OfferDestroyEvent::class => [
            OfferSanitizeDelete::class
        ],

        OfferUpdatedEvent::class => [
            // SendOfferInAnalysisNotification::class,
            // SendOfferActiveNotification::class,
            // SendOfferRefusedNotification::class,
            // SendOfferSuspendedNotification::class,
        ],

        OfferUpdateDependenciesEvent::class => [
            OfferUpdatePackageDependenciesNotification::class
        ],
        
        // Package events
        PackageCreatedEvent::class => [
            // SendPackageInAnalysisNotification::class,
        ],

        PackageUpdatedEvent::class => [
            // SendPackageInAnalysisNotification::class,
            // SendPackageActiveNotification::class,
            // SendPackageRefusedNotification::class,
            // SendPackageSuspendedNotification::class,
        ],

        BookingStatusEvent::class => [
            BookingProcessStatusChange::class
        ],

        BookingCreatedEvent::class => [
            SendBookingNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
