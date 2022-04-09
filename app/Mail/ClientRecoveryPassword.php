<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientRecoveryPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client, $_data=null)
    {
        $this->client   = $client;
        $this->_data    = $_data;
        $this->subject  = __('mail.client.recov_pass.head');

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = 'email.clients.recovery_password';
        $string  = (String) view($email_template,
            ['client' => $this->client, '_data' => $this->_data]);

        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
