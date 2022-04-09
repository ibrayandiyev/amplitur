<?php

namespace App\Mail\Backoffice;

use App\Enums\PersonType;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientRegistryBackofficeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client   = $client;
        $name           = $this->client->getNameByType();
        $this->subject  = __('mail.backoffice.client.created') . " - " . $name;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = 'email.backoffice.client_registry';
        $string  = (String) view($email_template,
            ['client' => $this->client]);

        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
