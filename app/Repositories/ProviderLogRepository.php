<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Provider;
use App\Models\ProviderLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ProviderLogRepository extends Repository
{
    /**
     * @var Provider
     */
    protected $targetProvider;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var Provider|null
     */
    protected $provider;

    public function __construct(ProviderLog $model)
    {
        $this->model = $model;
    }

    /**
     * [setTargetProvider description]
     *
     * @param   Provider             $provider  [$provider description]
     *
     * @return  ProviderLogRepository           [return description]
     */
    public function setTargetProvider(Provider $provider): ProviderLogRepository
    {
        $this->targetProvider = $provider;

        return $this;
    }

    /**
     * [setUser description]
     *
     * @param   User                 $user  [$user description]
     *
     * @return  ProviderLogRepository       [return description]
     */
    public function setUser(?User $user): ProviderLogRepository
    {
        $this->user = $user;

        return $this;
    }

    /**
     * [setProvider description]
     *
     * @param   Provider             $provider  [$provider description]
     *
     * @return  ProviderLogRepository           [return description]
     */
    public function setProvider(?Provider $provider): ProviderLogRepository
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        if (!empty($this->targetProvider)) {
            $attributes['target_provider_id'] = $this->targetProvider->id;
        }

        if (!empty($this->user)) {
            $attributes['user_id'] = $this->user->id;
        }

        if (!empty($this->provider)) {
            $attributes['provider_id'] = $this->provider->id;
        }

        return $attributes;
    }

    /**
     * [getByTargetProvider description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  [type]               [return description]
     */
    public function getByTargetProvider(Provider $provider, $reader): Collection
    {
        $query = $this->model
            ->where('target_provider_id', $provider->id);

        if ($reader instanceof Client) {
            $query->whereIn('level', [2, 16]);
        } else if ($reader instanceof User) {
            $query->where('level', '>=', 1);
        } else if ($reader instanceof Provider) {
            $query->where('level', '>=', 4);
        }

        $query->orderBy('created_at', 'desc');
        
        $logs = $query->get();

        return $logs;
    }
}