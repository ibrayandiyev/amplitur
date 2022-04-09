<?php

namespace App\Models;

use App\Enums\PersonType;
use App\Models\Relationships\HasManyBankAccounts;
use App\Models\Relationships\HasManyCompanies;
use App\Models\Relationships\HasManyOffers;
use App\Models\Relationships\HasManyPackages;
use App\Models\Relationships\HasManyPrebookings;
use App\Models\Relationships\MorphManyContacts;
use App\Models\Relationships\MorphOneAddress;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasPersonTypeLabels;
use App\Models\Traits\HasProcessStatusLabels;
use App\Models\Traits\Permissions;
use App\Notifications\Backend\Events\EventUpdateNotification;
use App\Notifications\Backend\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Provider extends Authenticatable
{
    use MorphOneAddress,
        MorphManyContacts,
        HasManyPrebookings,
        HasManyCompanies,
        HasDateLabels,
        HasPersonTypeLabels,
        HasProcessStatusLabels,
        HasManyBankAccounts,
        HasManyOffers,
        HasManyPackages,
        HasFactory,
        Notifiable,
        Permissions;

    protected $guard = 'providers';

    protected $fillable = [
        'name',
        'legal_name',
        'company_name',
        'email',
        'birthdate',
        'identity',
        'document',
        'uf',
        'passport',
        'registry',
        'gender',
        'language',
        'username',
        'password',
        'is_active',
        'is_valid',
        'is_newsletter_subscriber',
        'type',
        'validation_token',
        'terms_use',
        'ip',
        'primary_document',
        'responsible_name',
        'responsible_email',
        'status',
        'created_at',
        'country',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'birthdate'
    ];

    /**
     * Get formatted person type label
     * 
     * @return string
     */
    public function getTypeLabelAttribute(): ?string
    {
        if ($this->type  == PersonType::LEGAL) {
            return '<span class="label label-light-success">Pessoa Jurídica</span>';
        } else {
            return '<span class="label label-light-warning">Pessoa Física</span>';
        }
    }

    /**
     * [isActive description]
     *
     * @return  bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * [isMaster description]
     *
     * @return  bool
     */
    public function isMaster(): bool
    {
        return false;
    }

    /**
     * [isManager description]
     *
     * @return  bool    [return description]
     */
    public function isManager(): bool
    {
        return false;
    }

    /**
     * [isAdmin description]
     *
     * @return  bool    [return description]
     */
    public function isAdmin(): bool
    {
        return false;
    }

    /**
     * [isProvider description]
     *
     * @return  [type]  [return description]
     */
    public function isProvider(): bool
    {
        return true;
    }

    /**
     * [generateValidationToken description]
     *
     * @return  [type]  [return description]
     */
    public function generateValidationToken(): bool
    {
        $this->validation_token = md5(date("Y-m-dHis", $this->id));
        return $this->validation_token;
    }

    /**
     * [setIsActive description]
     *
     * @return  [type]  [return description]
     */
    public function setIsActive($value): bool
    {
        $this->is_active = $value;
        return $this->is_active;
    }

    /**
     * [setIsValid description]
     *
     * @return  [type]  [return description]
     */
    public function setIsValid($value): bool
    {
        $this->is_valid = $value;
        return $this->is_valid;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function sendEventChangeNotification($event)
    {
        $this->notify(new EventUpdateNotification($this, $event));
    }
}
