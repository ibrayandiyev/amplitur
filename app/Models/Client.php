<?php

namespace App\Models;

use App\Enums\PersonType;
use App\Models\Relationships\HasManyBookingLegacies;
use App\Models\Relationships\HasManyBookings;
use App\Models\Relationships\MorphOneAddress;
use App\Models\Relationships\HasManyPrebookings;
use App\Models\Relationships\MorphManyContacts;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasIsActiveLabels;
use App\Models\Traits\HasPersonTypeLabels;
use App\Notifications\Backend\Events\EventUpdateNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Client extends Authenticatable
{
    use MorphOneAddress,
        MorphManyContacts,
        HasManyPrebookings,
        HasManyBookings,
        HasManyBookingLegacies,
        HasDateLabels,
        HasPersonTypeLabels,
        HasIsActiveLabels,
        Notifiable,
        HasFactory;

    protected $guard = 'clients';

    protected $fillable = [
        'id',
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
        'primary_document',
        'responsible_name',
        'responsible_email',
        'validation_token',
        'remember_token',
        'verification_token',
        'created_at',
        'country',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'birthdate',
        'created_at',
        'updated_at',
    ];

    protected $_searchable = [
        'search_for',
        'wildcard'
    ];

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
     * [getNameByType description]
     *
     * @return  [type]  [return description]
     */
    public function getNameByType()
    {
        if($this->type == PersonType::FISICAL){
            return $this->name;
        }
        return $this->company_name;
    }

    /**
     * [getFirstName description]
     *
     * @return  [type]  [return description]
     */
    public function getFirstName()
    {
        if($this->type == PersonType::FISICAL){
            return explode(' ', $this->name)[0];
        }
        if($this->type == PersonType::LEGAL){
            return explode(' ', $this->legal_name)[0];
        }
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

    public function getIsSearchable($key = null){
        if(is_array($this->_searchable)){
            if(array_key_exists($key, $this->_searchable)){
                return true;
            }
        }
        return false;
    }

    public function sendEventChangeNotification($event)
    {
        $this->notify(new EventUpdateNotification($this, $event));
    }
}
