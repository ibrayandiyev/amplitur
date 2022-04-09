<?php

namespace App\Models;

use App\Models\Relationships\BelongsToClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use BelongsToClient,
        HasFactory;

    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'name',
        'address',
        'number',
        'neighborhood',
        'complement',
        'city',
        'state',
        'zip',
        'country',
        'is_primary',
        'latitude',
        'longitude',
    ];

    /**
     * Get address city as model if it exists
     * 
     * @return null|string|City
     */
    public function city()
    {
        $city = City::find($this->city);

        if (!$city) {
            return $this->city;
        }

        return $city;
    }

        /**
     * Get address city as model if it exists
     * 
     * @return null|string|City
     */
    public function getCityName()
    {
        $city = $this->city();;

        if (!$city) {
            return null;
        }

        return $city->name;
    }

    /**
     * Get address state as model if it exists
     * 
     * @return null|string|State
     */
    public function state()
    {
        $state = State::where('iso2', $this->state)->where('country_code', $this->country)->first();

        if (!$state) {
            return $this->state;
        }

        return $state;
    }

    /**
     * Get address country as model if it exists
     * 
     * @return null|string|City
     */
    public function country()
    {
        $country = Country::where('iso2', $this->country)->first();

        if (!$country) {
            return $this->country;
        }

        return $country;
    }
}
