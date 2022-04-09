<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Provider;
use App\Models\ClientLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ClientLogRepository extends Repository
{
    /**
     * @var Client
     */
    protected $targetClient;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var Provider|null
     */
    protected $provider;

    public function __construct(ClientLog $model)
    {
        $this->model = $model;
    }

    /**
     * [setTargetClient description]
     *
     * @param   Client               $client  [$client description]
     *
     * @return  ClientLogRepository           [return description]
     */
    public function setTargetClient(Client $client): ClientLogRepository
    {
        $this->targetClient = $client;

        return $this;
    }

    /**
     * [setUser description]
     *
     * @param   User                 $user  [$user description]
     *
     * @return  ClientLogRepository         [return description]
     */
    public function setUser(?User $user): ClientLogRepository
    {
        $this->user = $user;

        return $this;
    }

    /**
     * [setProvider description]
     *
     * @param   Provider             $provider  [$provider description]
     *
     * @return  ClientLogRepository             [return description]
     */
    public function setProvider(?Provider $provider): ClientLogRepository
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        if (!empty($this->targetClient)) {
            $attributes['target_client_id'] = $this->targetClient->id;
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
     * [getByTargetClient description]
     *
     * @param   Client  $client  [$client description]
     *
     * @return  [type]           [return description]
     */
    public function getByTargetClient(Client $client, $reader): Collection
    {
        $query = $this->model
            ->where('target_client_id', $client->id);

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
