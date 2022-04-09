<?php

namespace App\Repositories;

use App\Models\Event;
use App\Models\Prebooking;

class PrebookingRepository extends Repository
{
    /**
     * @var Event
     */
    protected $event;

    public function __construct(Prebooking $model)
    {
        $this->model = $model;
    }

    /**
     * [setEvent description]
     *
     * @param   Event                 $event  [$event description]
     *
     * @return  PrebookingRepository          [return description]
     */
    public function setEvent(Event $event): PrebookingRepository
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        if (!empty($this->event)) {
            $attributes['event_id'] = $this->event->id;
        }

        return $attributes;
    }
}