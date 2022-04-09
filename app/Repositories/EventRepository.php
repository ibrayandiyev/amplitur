<?php

namespace App\Repositories;

use App\Exports\EventsExport;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Flag;
use App\Models\Package;
use App\Repositories\Concerns\ActionExport;
use App\Repositories\Concerns\ActionImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class EventRepository extends Repository
{
    use ActionExport,
        ActionImport;

    protected $importClass = 'App\Imports\EventsImport';
    protected $importQueueName = 'import-event';

    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes = $this->handleAddress($attributes);

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes = $this->handleAddress($attributes);

        return $attributes;
    }

    /**
     * Handle address information
     *
     * @param   array  $attributes
     *
     * @return  array
     */
    protected function handleAddress(array $attributes): array
    {
        if (isset($attributes['address'])) {
            $address = $attributes['address'];
            $attributes['country'] = $address['country'] ?? null;
            $attributes['city'] = $address['city'] ?? null;
            $attributes['state'] = $address['state'] ?? null;
            unset($attributes['address']);
        }
        
        return $attributes;
    }

    /**
     * Download repository as xls
     *
     * @return  Response
     */
    public function download()
    {
        $hash = time();

        return Excel::download(new EventsExport, "EVENTS_{$hash}.xlsx");
    }

    /**
     * Event called on importing
     *
     * @return  Flag
     */
    public function importing()
    {
        $flag = Flag::where('name', 'IS_IMPORTING_EVENTS')->first();

        if (!$flag) {
            return Flag::create([
                'name' => 'IS_IMPORTING_EVENTS',
                'value' => true,
            ]);
        }

        $flag->value = true;
        $flag->save();

        return $flag;
    }

    /**
     * Event called on importing is ended
     *
     * @return  Flag
     */
    public function imported()
    {
        $flag = Flag::where('name', 'IS_IMPORTING_EVENTS')->first();

        if (!$flag) {
            return Flag::create([
                'name' => 'IS_IMPORTING_EVENTS',
                'value' => false,
            ]);
        }

        $flag->value = false;
        $flag->save();

        return $flag;
    }

    /**
     * Event called on importing is fail
     *
     * @return  Flag
     */
    public function importFail(bool $failed)
    {
        $flag = Flag::where('name', 'FAILED_IMPORTING_EVENTS')->first();

        if (!$flag) {
            return Flag::create([
                'name' => 'FAILED_IMPORTING_EVENTS',
                'value' => $failed,
            ]);
        }

        $flag->value = $failed;
        $flag->save();

        return $flag;
    }

    /**
     * [listOnPrebooking description]
     *
     * @param   int         $limit          [$limit description]
     * @param   bool        $skipExclusive  [$skipExclusive description]
     *
     * @return  Collection                  [return description]
     */
    public function listOnPrebooking(int $limit = 6, bool $skipExclusive = true): Collection
    {
        $query = $this->model
            ->inRandomOrder()
            ->limit($limit);

        if ($skipExclusive) {
            $query = $query->where('is_exclusive', false);
        }
        
        $events = $query->get();

        return $events;
    }

    /**
     * [search description]
     *
     * @param   [type]  $string  [$string description]
     *
     * @return  [type]           [return description]
     */
    public function search($string)
    {
        $packages = $this->model
            ->where('name', 'like', "%{$string}%")
            ->where('is_exclusive', false)
            ->get();

        return $packages;
    }

    /**
     * [search description]
     *
     * @param   [type]  $string  [$string description]
     *
     * @return  [type]           [return description]
     */
    public function notifyProviderEventUpdate(Event $event)
    {
        $_providers     = $_clients = null;
        $sent           = 0;
        $entities       = Package::where("event_id", $event->id)->get();
        $_packages      = $entities->pluck("id");
        $bookings       = Booking::whereIn("package_id", $_packages)->get();
        foreach($bookings as $booking){
            $providerId                 = $booking->offer->provider_id;
            $clientId                   = $booking->client_id;
            $_providers[$providerId]    = $booking->offer->provider;
            $_clients[$clientId]        = $booking->client;
        }
        if($_providers != null){
            foreach($_providers as $provider){
                $provider->sendEventChangeNotification($event);
                $sent = 1;
            }
        }
        if($_clients != null){
            foreach($_clients as $client){
                // Use with careful this method.
                //$client->sendEventChangeNotification($event);
                $sent = 1;
            }
        }
        
        return $sent;
    }
}