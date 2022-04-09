<?php

namespace App\Listeners\Providers;

use App\Events\Providers\CompanyCreatedEvent;
use App\Mail\Providers\CompanyCreatedOffice;
use App\Mail\Providers\CompanyRegistryMail;
use App\Models\Company;
use App\Models\Provider;
use Illuminate\Support\Facades\Mail;

class SendCompanyWelcomeNotification
{
    /**
     * Handle the event.
     *
     * @param  CompanyCreatedEvent  $event
     * @return void
     */
    public function handle(CompanyCreatedEvent $event)
    {
        $provider   = $event->company->provider;
        $company    = $event->company;

        $this->sendProviderNotification($provider, $company);
    }

    /**
     * [sendProviderNotification description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  [type]               [return description]
     */
    protected function sendProviderNotification(Provider $provider, Company $company)
    {
        Mail::to($provider->email)->send(new CompanyRegistryMail($provider, $company));
    }

    /**
     * Handle a job failure.
     *
     * @param  CompanyCreatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(CompanyCreatedEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
