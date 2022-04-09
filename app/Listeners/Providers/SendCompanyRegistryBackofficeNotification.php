<?php

namespace App\Listeners\Providers;

use App\Events\Providers\CompanyCreatedEvent;
use App\Mail\Providers\CompanyCreatedOffice;
use App\Mail\Providers\CompanyRegistryBackofficeMail;
use App\Mail\Providers\CompanyRegistryMail;
use App\Models\Company;
use App\Models\Provider;
use Illuminate\Support\Facades\Mail;

class SendCompanyRegistryBackofficeNotification
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

        $this->notifyCompanyBackofficeRegistry($provider, $company);
    }

    /**
     * [sendOfficeNotification description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  [type]               [return description]
     */
    protected function notifyCompanyBackofficeRegistry(Provider $provider, Company $company)
    {
        $emailBackoffice = emailBackoffice();
        if($emailBackoffice != null){
            Mail::to($emailBackoffice)->send(new CompanyRegistryBackofficeMail($provider, $company));
        }
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
