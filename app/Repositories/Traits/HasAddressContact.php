<?php

namespace App\Repositories\Traits;

use App\Models\Contact;
use App\Repositories\AddressRepository;
use App\Repositories\ContactRepository;
use Illuminate\Database\Eloquent\Model;

trait HasAddressContact
{
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

        if (isset($attributes['responsible'])) {
            $attributes['responsible'] = $this->parseResponsibleAttribute($attributes['responsible'], count($attributes['type']));
        }

        if (isset($attributes['id'])) {
            for ($i = 0; $i < count($attributes['type']); $i++) {
                $contact = $repository->find($attributes['id'][$i]);

                if (!$contact) {
                    $this->createContact($resource, $attributes['responsible'][$i] ?? null, $attributes['type'][$i], $attributes['value'][$i]);
                } else {
                    $this->updateContact($contact, $attributes['responsible'][$i] ?? null, $attributes['type'][$i], $attributes['value'][$i]);
                }
            }
        } else {
            for ($i = 0; $i < count($attributes['type']); $i++) {
                $this->createContact($resource, $attributes['responsible'][$i] ?? null, $attributes['type'][$i], $attributes['value'][$i]);
            }
        }
    }

    /**
     * [createContact description]
     *
     * @param   Model    $resource     [$resource description]
     * @param   string   $responsible  [$responsible description]
     * @param   string   $type         [$type description]
     * @param   string   $value        [$value description]
     *
     * @return  Contact                [return description]
     */
    protected function createContact(Model $resource, ?string $responsible, ?string $type, ?string $value): Contact
    {
        $repository = app(ContactRepository::class);

        $contact = $repository->store([
            'contactable_id' => $resource->id,
            'contactable_type' => get_class($resource),
            'responsible' => $responsible,
            'type' => $type,
            'value' => $value,
        ]);

        return $contact;
    }

    /**
     * [updateContact description]
     *
     * @param   Model    $resource     [$resource description]
     * @param   string   $responsible  [$responsible description]
     * @param   string   $type         [$type description]
     * @param   string   $value        [$value description]
     *
     * @return  Contact                [return description]
     */
    protected function updateContact(Model $resource, ?string $responsible, ?string $type, ?string $value): Contact
    {
        $repository = app(ContactRepository::class);

        $contact = $repository->update($resource, [
            'responsible' => $responsible,
            'type' => $type,
            'value' => $value,
        ]);

        return $contact;
    }

    /**
     * Process resource adress
     *
     * @param   Model  $resource
     * @param   array  $attributes
     *
     * @return  void
     */
    protected function handleAddress(Model $resource, array $attributes): void
    {
        $repository = app(AddressRepository::class);

        if (isset($resource->address->id)) {
            $repository->update($resource->address, $attributes);
        } else {
            $attributes['addressable_id'] = $resource->id;
            $attributes['addressable_type'] = get_class($resource);
            $repository->store($attributes);
        }

        $resource->country = $attributes['country'];
        $resource->save();
    }

    /**
     * Parse array of responsibles when it's not complete
     *
     * @param   array  $responsibles
     * @param   int    $count
     *
     * @return  array
     */
    private function parseResponsibleAttribute(array $responsibles, int $count): array
    {
        
        if ($count == count($responsibles)) {
            return $responsibles;
        }

        $group = round($count / count($responsibles));

        $attributes = [];

        foreach ($responsibles as $responsible) {
            $attributes[] = $responsible;
            $attributes[] = $responsible;
        }

        return $attributes;
    }

    public function handlesAddressImport($resource, array $attributes): void
    {
        $repository = app(AddressRepository::class);

        if (isset($resource->address->id)) {
            $repository->update($resource->address, $attributes);
        } else {
            $attributes['addressable_id'] = $resource->id;
            $attributes['addressable_type'] = get_class($resource);
            $repository->store($attributes);
        }
    }

    public function handlesContactImport($resource, array $attributes): void
    {
        $repository = app(ContactRepository::class);

        $entity = $resource->contacts
            ->where("contactable_id", $resource->id)
            ->where("contactable_type", get_class($resource))
            ->where("value", $attributes['value'])
            ->first();
        if ($entity) {
            $repository->update($entity, $attributes);
        } else {
            $attributes['contactable_id']   = $resource->id;
            $attributes['contactable_type'] = get_class($resource);
            $repository->store($attributes);
        }
    }
}