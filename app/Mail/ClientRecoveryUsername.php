<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientRecoveryUsername extends Mailable
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
        $this->subject  = __('mail.client.recov_user.head');

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = 'email.clients.recovery_username';
        $string  = (String) view($email_template,
            ['client' => $this->client]);

        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
