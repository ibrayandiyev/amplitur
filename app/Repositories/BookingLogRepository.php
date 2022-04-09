<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Client;
use App\Models\Provider;
use App\Models\User;
use App\Repositories\BookingLogConcerns\BookingLogActions;
use Illuminate\Database\Eloquent\Collection;

class BookingLogRepository extends Repository
{
    use BookingLogActions;

    /**
     * @var Client
     */
    protected $targetClient;

    /**
     * @var Booking
     */
    protected $targetBooking;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @var Provider|null
     */
    protected $provider;

    public function __construct(BookingLog $model)
    {
        $this->model = $model;
    }

    /**
     * Filter a resource
     *
     * @param   array  $id
     *
     * @return  Model|Collection|null
     */
    public function filter(array $params, ?int $limit = null): Collection
    {
        $query = $this->model->query();

        if (isset($params['start_date'])) {
            $params['start_date']   = convertDate($params['start_date']);
            $query = $query->whereDate('booking_logs.created_at', ">=", $params['start_date']);
        }

        if (isset($params['end_date'])) {
            $params['end_date']     = convertDate($params['end_date']);
            $query = $query->whereDate('booking_logs.created_at', "<=", $params['end_date']);
        }

        if (isset($params['booking_id'])) {
            $query = $query->where('booking_logs.target_booking_id', $params['booking_id']);
        }

        if (isset($params['operation'])) {
            $query = $query->where('booking_logs.operation', $params['operation']);
        }

        if (!empty($limit)) {
            $query = $query->limit($limit);
        }

        $resources = $query->get();

        return $resources;
    }

    /**
     * [setTargetClient description]
     *
     * @param   Client               $client  [$client description]
     *
     * @return  BookingLogRepository           [return description]
     */
    public function setTargetClient(Client $client): BookingLogRepository
    {
        $this->targetClient = $client;

        return $this;
    }

    /**
     * [setTargetBooking description]
     *
     * @param   Booking               $booking  [$booking description]
     *
     * @return  BookingLogRepository           [return description]
     */
    public function setTargetBooking(Booking $booking): BookingLogRepository
    {
        $this->targetBooking = $booking;

        return $this;
    }

    /**
     * [setUser description]
     *
     * @param   User                 $user  [$user description]
     *
     * @return  BookingLogRepository         [return description]
     */
    public function setUser(?User $user): BookingLogRepository
    {
        $this->user = $user;

        return $this;
    }

    /**
     * [setProvider description]
     *
     * @param   Provider             $provider  [$provider description]
     *
     * @return  BookingLogRepository             [return description]
     */
    public function setProvider(?Provider $provider): BookingLogRepository
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

        if (!empty($this->targetBooking)) {
            $attributes['target_booking_id'] = $this->targetBooking->id;
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
    public function getByTargetBooking(Booking $booking, $reader): Collection
    {
        $query = $this->model
            ->where('target_booking_id', $booking->id);

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
