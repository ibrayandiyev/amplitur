<?php

namespace App\Repositories;

use App\Enums\AccessStatus;
use App\Enums\ProcessStatus;
use App\Events\Providers\ProviderCreatedEvent;
use App\Events\Providers\ProviderUpdatedEvent;
use App\Exceptions\DeleteRelationsException;
use App\Models\Booking;
use App\Models\Provider;
use App\Repositories\Concerns\ActionExport;
use App\Repositories\Traits\HasAddressContact;
use App\Repositories\Traits\HasPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProviderRepository extends Repository
{
    use HasAddressContact,
        HasPassword,
        ActionExport;

    public function __construct(Provider $model)
    {
        $this->model = $model;
    }

    /**
     * [listActives description]
     *
     * @return  Collection[return description]
     */
    public function listActives(): Collection
    {
        $query = $this->model
            ->where('status', 'active');

        return $query->get();
    }


    /**
     * [setActor description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  ProviderRepository[return description]
     */
    public function setActor($user): ProviderRepository
    {
        $this->actor = $user;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeDelete(Model $resource): Model
    {
        // Check if there is relations registered
        $entityCompanies    = app(CompanyRepository::class)->findByProviderId($resource->id);
        $entityOffers       = app(OfferRepository::class)->findByProviderId($resource->id);
        $entityPackages     = app(PackageRepository::class)->findByProviderId($resource->id);
        $_packages          = ($entityPackages->pluck("id")->all());
        $entitiesBooking    = (new Booking())->whereIn("package_id", $_packages)->get();
        if($entityOffers->count() || 
            $entityPackages->count() || 
            $entityCompanies->count() || 
            $entitiesBooking->count()){
            throw new DeleteRelationsException();
        }
        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes = $this->parseProviderName($attributes);
        $attributes = $this->handlePassword($attributes);

        if (isset($attributes['status'])) {
            $attributes['is_active'] = $attributes['status'] == AccessStatus::ACTIVE;
        } else {
            $attributes['is_active'] = false;
        }    
        $attributes['ip']   = ip();
        return $attributes;
    }

    /**
     * @inhreted
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterStore($resource, $attributes);
        $resource->generateValidationToken();
        $resource->save();

        $this->handleContacts($resource, $attributes['contacts']);
        $this->handleAddress($resource, $attributes['address']);

        ProviderCreatedEvent::dispatch($resource->fresh());

        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes = $this->parseProviderName($attributes);
        $attributes = $this->handlePassword($attributes);

        if (isset($attributes['status'])) {
            $attributes['is_active'] = $attributes['status'] == AccessStatus::ACTIVE;
        } else {
            $attributes['is_active'] = false;
        }  

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterStore($resource, $attributes);

        $this->handleContacts($resource, $attributes['contacts']);
        $this->handleAddress($resource, $attributes['address']);

        ProviderUpdatedEvent::dispatch($resource->fresh());

        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeFilter(Builder $builder, array $params): Builder
    {
        $wheres = $builder->getQuery()->wheres;

        foreach ($wheres as $i => $where) {
            foreach ($where as $key => $value) {
                if ($where[$key] != 'country') {
                    continue;
                }

                if ($wheres[$i]['value'] != 'other') {
                    $wheres[$i]['operator'] = '!=';
                    $wheres[$i]['value'] = 'BR';
                    $builder->getQuery()->bindings['where'][$i] = 'BR';
                }
            }
        }

        $builder->getQuery()->wheres = $wheres;

        return $builder;
    }

    /**
     * Parse provider name copying company_name to name attribute
     *
     * @param   array  $attributes
     *
     * @return  array
     */
    protected function parseProviderName(array $attributes): array
    {
        if (isset($attributes['company_name'])) {
            $attributes['name'] = $attributes['company_name'];
        }

        return $attributes;
    }

    /**
     * [register description]
     *
     * @param   array     $attributes  [$attributes description]
     *
     * @return  Provider               [return description]
     */
    public function register(array $attributes): Provider
    {
        $attributes['is_active'] = false;
        $attributes['status'] = ProcessStatus::IN_ANALYSIS;
        $provider = $this->store($attributes);

        return $provider;
    }

    /**
     * [validateToken description]
     *
     * @param   array     $attributes  [$attributes description]
     *
     * @return  Provider               [return description]
     */
    public function validateToken(array $attributes)
    {
        $provider = app(Provider::class)->where("validation_token", $attributes['validation_token'])->first();

        if(!$provider){
            return null;
        }
        $provider->setIsValid(1);
        $provider->status = ProcessStatus::ACTIVE;
        $provider->setIsActive(1);
        $provider->save();
        return $provider;
    }
}
