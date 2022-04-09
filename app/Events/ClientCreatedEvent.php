<?php

namespace App\Events;

use App\Models\Client;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Client
     */
    public $client;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
