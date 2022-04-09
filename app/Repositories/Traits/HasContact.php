<?php

namespace App\Repositories\Traits;

use App\Repositories\ContactRepository;
use Illuminate\Database\Eloquent\Model;

trait HasContact
{
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterStore($resource, $attributes);
        
        $this->handleContacts($resource, $attributes['contacts']);

        return $resource;
    }

    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterUpdate($resource, $attributes);

        $this->handleContacts($resource, $attributes['contacts']);

        return $resource;
    }

    /**
     * Process resource contacts
     *
     * @param   Model  $resource
     * @param   array  $attributes
     *
     * @return  void
     */
    protected function handleContacts(Model $resource, array $attributes): void
    {
        $repository = app(ContactRepository::class);

        if (isset($attributes['id'])) {
            for ($i = 0; $i < count($attributes['type']); $i++) {
                $contact = $repository->find($attributes['id'][$i]);

                $repository->update($contact, [
                    'type' => $attributes['type'][$i],
                    'value' => $attributes['value'][$i],

                ]);
            }
        } else {
            for ($i = 0; $i < count($attributes['type']); $i++) {
                $repository->store([
                    'contactable_id' => $resource->id,
                    'contactable_type' => get_class($resource),
                    'type' => $attributes['type'][$i],
                    'value' => $attributes['value'][$i],
                ]);
            }
        }
    }
}