<?php

namespace App\Listeners\Providers;

use App\Events\Providers\ProviderCreatedEvent;
use App\Mail\Backoffice\ProviderRegistryBackofficeMail;
use App\Mail\Providers\ProviderRegistryMail;
use App\Models\Provider;
use Illuminate\Support\Facades\Mail;

class SendProviderWelcomeNotification
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

        $this->sendProviderNotification($provider);
    }

    /**
     * [sendProviderNotification description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  [type]               [return description]
     */
    protected function sendProviderNotification(Provider $provider)
    {
        Mail::to($provider->email)->send(new ProviderRegistryMail($provider));
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
