<?php

namespace App\Listeners\Providers;

use App\Events\Providers\ProviderCreatedEvent;
use App\Mail\Backoffice\ProviderRegistryBackofficeMail;
use App\Models\Provider;
use Illuminate\Support\Facades\Mail;

class SendProviderRegistryBackofficeNotification
{
    /**
     * Handle the event.
     *
     * @param  ProviderCreatedEvent  $event
     * @return void
     */
    public function handle(ProviderCreatedEvent $event)
    {
        $provider = $event->provider;

        $this->sendBackofficeNotification($provider);
    }

    /**
     * [sendOfficeNotification description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  [type]               [return description]
     */
    protected function sendBackofficeNotification(Provider $provider)
    {
        $emailBackoffice = emailBackoffice();
        if($emailBackoffice != null){
            Mail::to($emailBackoffice)->send(new ProviderRegistryBackofficeMail($provider));
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  ProviderCreatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(ProviderCreatedEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
