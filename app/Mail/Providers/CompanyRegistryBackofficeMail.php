<?php

namespace App\Mail\Providers;

use App\Models\Company;
use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyRegistryBackofficeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Provider $provider, Company $company)
    {
        $this->provider = $provider;
        $this->company  = $company;
        $this->subject  = __('mail.provider.company.created') . " - " . $this->provider->name . " - " . $this->company->company_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = 'email.backoffice.providers.company-created';
        $address    = $this->company->address;
        $string  = (String) view($email_template,
            ['provider' => $this->provider, 'company' => $this->company, 'address' => $address]);
        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
