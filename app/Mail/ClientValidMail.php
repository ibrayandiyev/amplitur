<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientValidMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->subject  = __('mail.client.geral.validated');

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = 'email.clients.registry_valid';
        $string  = (String) view($email_template,
            ['client' => $this->client]);

        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
