<?php

namespace App\Listeners\Clients;

use App\Events\ClientCreatedEvent;
use App\Mail\Backoffice\ClientRegistryBackofficeMail;
use App\Models\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendClientRegistryBackofficeNotification implements ShouldQueue
{
    public $queue = 'notifications';

    /**
     * @var Client
     */
    private $client;

    /**
     * Handle the event.
     *
     * @param  ClientCreatedEvent  $event
     * @return void
     */
    public function handle(ClientCreatedEvent $event)
    {
        $this->client     = $event->client;
        $this->notifyClientBackofficeRegistry($this->client);
    }

    /**
     * [notifyClientRegistry description]
     *
     * @param   Client  $client  [$client description]
     *
     * @return  [type]           [return description]
     */
    private function notifyClientBackofficeRegistry(Client $client)
    {
        $emailBackoffice = emailBackoffice();
        if($emailBackoffice != null){
            Mail::to($emailBackoffice)->send(new ClientRegistryBackofficeMail($client));
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  ClientCreatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(ClientCreatedEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
