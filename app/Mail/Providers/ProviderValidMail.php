<?php

namespace App\Mail\Providers;

use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderValidMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
        $this->subject  = __('mail.provider.validated');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = 'email.providers.registry_valid';
        $string  = (String) view($email_template,
                ['provider' => $this->provider]);

        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
