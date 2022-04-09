<?php

namespace App\Mail\Backoffice;

use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProviderRegistryBackofficeMail extends Mailable
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
        $this->subject  = __('mail.provider.created') . " - " . $this->provider->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = 'email.backoffice.providers.provider-created';
        $string  = (String) view($email_template,
        ['provider' => $this->provider]);

        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
