<?php

namespace App\Models\Relationships;

use App\Enums\ContactType;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

trait MorphManyContacts
{
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Returns financial contacts
     *
     * @return  Collection|null
     */
    public function financialContacts(): ?Collection
    {
        $contacts = $this->contacts()->where(function ($query) {
            $query->where('contactable_id', $this->id);
        })->where(function ($query) {
            $query->where('type', ContactType::FINANCIAL_PHONE)
                ->orWhere('type', ContactType::FINANCIAL_EMAIL);
        })->get();

        return $contacts;
    }

    /**
     * Returns booking contacts
     *
     * @return  Collection|null
     */
    public function bookingContacts(): ?Collection
    {
        $contacts = $this->contacts()->where(function ($query) {
            $query->where('contactable_id', $this->id);
        })->where(function ($query) {
            $query->where('type', ContactType::BOOKING_PHONE)
                ->orWhere('type', ContactType::BOOKING_EMAIL);
        })->get();

        return $contacts;
    }
}
