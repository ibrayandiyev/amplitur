<?php

namespace App\Models;

use App\Enums\ContactType;
use App\Enums\ProcessStatus;
use App\Models\Relationships\BelongsToProvider;
use App\Models\Relationships\HasManyDocuments;
use App\Models\Relationships\HasManyOffers;
use App\Models\Relationships\HasOneBankAccount;
use App\Models\Relationships\MorphManyContacts;
use App\Models\Relationships\MorphOneAddress;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasProcessStatusLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    use MorphOneAddress,
        MorphManyContacts,
        BelongsToProvider,
        HasDateLabels,
        HasOneBankAccount,
        HasManyOffers,
        HasProcessStatusLabels,
        HasManyDocuments,
        HasFactory;

    protected $fillable = [
        'provider_id',
        'company_name',
        'legal_name',
        'website',
        'logo',
        'registry',
        'country',
        'status',
        'language',
        'terms_use',
        'ip'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get friendly financial contact information
     *
     * @return  object
     */
    protected function getFinancialContactInfoAttribute(): object
    {
        $contacts = $this->financialContacts();

        $contact = [
            'responsible' => $contacts->first()->responsible ?? null,
            'phone' => $contacts->where('type', ContactType::FINANCIAL_PHONE)->first()->value ?? null,
            'email' => $contacts->where('type', ContactType::FINANCIAL_EMAIL)->first()->value ?? null,
            'phone_contact_id' => $contacts->where('type', ContactType::FINANCIAL_PHONE)->first()->id ?? null,
            'email_contact_id' => $contacts->where('type', ContactType::FINANCIAL_EMAIL)->first()->id ?? null,
        ];

        return (object) $contact;
    }

    /**
     * Get friendly booking contact information
     *
     * @return  object
     */
    protected function getBookingContactInfoAttribute(): object
    {
        $contacts = $this->bookingContacts();

        $contact = [
            'responsible' => $contacts->first()->responsible ?? null,
            'phone' => $contacts->where('type', ContactType::BOOKING_PHONE)->first()->value ?? null,
            'email' => $contacts->where('type', ContactType::BOOKING_EMAIL)->first()->value ?? null,
            'phone_contact_id' => $contacts->where('type', ContactType::BOOKING_PHONE)->first()->id ?? null,
            'email_contact_id' => $contacts->where('type', ContactType::BOOKING_EMAIL)->first()->id ?? null,
        ];

        return (object) $contact;
    }

    public function getStatusTitle(){
        switch($this->status){
            case ProcessStatus::ACTIVE:
                return __('resources.process-statues.active');
                break;
            case ProcessStatus::SUSPENDED:
                return __('resources.process-statues.suspended');
                break;
            case ProcessStatus::IN_ANALYSIS:
                return __('resources.process-statues.in-analysis');
                break;
        }
    }
}
