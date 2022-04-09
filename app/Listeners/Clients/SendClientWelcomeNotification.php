<?php

namespace App\Listeners\Clients;

use App\Events\ClientCreatedEvent;
use App\Mail\ClientRegistryMail;
use App\Models\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendClientWelcomeNotification implements ShouldQueue
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
        $this->notifyClientRegistry($this->client);
    }

    /**
     * [notifyClientRegistry description]
     *
     * @param   Client  $client  [$client description]
     *
     * @return  [type]           [return description]
     */
    private function notifyClientRegistry(Client $client)
    {
        Mail::to($client->email)->send(new ClientRegistryMail($client));
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
