<?php

namespace App\Repositories;

use App\Events\Providers\CompanyCreatedEvent;
use App\Models\Company;
use App\Models\Provider;
use App\Repositories\Concerns\ActionExport;
use App\Repositories\Traits\HasAddressContact;
use App\Repositories\Traits\HasBankAccount;
use App\Repositories\Traits\HasDocuments;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CompanyRepository extends Repository
{
    use HasAddressContact,
        HasBankAccount,
        HasDocuments,
        ActionExport;
  
    /**
     * @var Provider
     */
    public $provider;

    /**
     * User instance that is making the request
     *
     * @var User|Provider|null
     */
    protected $actor;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    /**
     * Set repository Provider relation
     *
     * @param   Provider           $provider
     *
     * @return  CompanyRepository
     */
    public function setProvider(Provider $provider): CompanyRepository
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * [setActor description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  CompanyRepository[return description]
     */
    public function setActor($user): CompanyRepository
    {
        $this->actor = $user;

        return $this;
    }

    /**
     * [findByProviderId description]
     *
     * @param   int  $providerId  [$providerId description]
     *
     * @return  [type]           [return description]
     */
    public function findByProviderId(int $providerId, bool $withAdditionals = false)
    {
        $query = $this->model->where('provider_id', $providerId);

        $entities = $query->get();

        return $entities;
    }

    /**
     * List all items of a resource
     *
     * @return  Collection
     */
    public function list(int $paginate = null, array $_params = null): Collection
    {
        if (!empty($this->actor) && $this->actor->isProvider()) {
            $query = $this->model->where('provider_id', $this->actor->id);
        } else {
            $query = $this->model;
        }

        if ($paginate) {
            $collection = $query->paginate($paginate);
        } else {
            $collection = $query->get();
        }

        $collection = $this->onAfterList($collection);

        return $collection;
    }

    /**
     * [listActives description]
     *
     * @return  Collection[return description]
     */
    public function listActives(?Provider $provider): Collection
    {
        $query = $this->model
            ->where('status', 'active');

        if (!empty($provider)) {
            $query = $this->model
                ->where('provider_id', $provider->id);
        } else if (!empty($this->provider)) {
            $query = $this->model
                ->where('provider_id', $this->provider->id);
        }

        return $query->get();
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['provider_id']  = $this->provider->id;
        $attributes['ip']           = ip();
        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterStore($resource, $attributes);

        $this->handleContacts($resource, $attributes['contacts']);
        $this->handleAddress($resource, $attributes['address']);
        $this->handleBankAccount($resource, $attributes['bank_account']);
        $this->handleUploadedDocuments($resource);

        CompanyCreatedEvent::dispatch($resource->fresh());

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterUpdate($resource, $attributes);

        $this->handleContacts($resource, $attributes['contacts']);
        $this->handleAddress($resource, $attributes['address']);
        $this->handleBankAccount($resource, $attributes['bank_account']);
        $this->handleUploadedDocuments($resource);

        return $resource;
    }

    /**
     * @inherited
     */
    protected function onAfterList(Collection $collection): Collection
    {
        if ($this->provider) {
            $collection = $this->provider->companies;
        }

        return $collection;
    }
}
