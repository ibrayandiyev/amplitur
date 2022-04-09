<?php

namespace App\Exports;

use App\Repositories\ClientRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    private $_clients;

    public function __construct($params = null)
    {
        $repository = app(ClientRepository::class);

        if($params != null){
            $this->_clients = $repository->filter($params);
        }else{
            $this->_clients = $repository->list();
        }
    }
    public function headings(): array
    {
        return [
            'id',
            'name',
            'company_name',
            'legal_name',
            'email',
            'birthdate',
            'identity',
            'uf',
            'document',
            'passport',
            'gender',
            'language',
            'is_active',
            'is_valid',
            'is_newsletter_subscriber',
            'type',
            'primary_document',
            'country',
            'state',
            'city',
            'responsible_name',
            'responsible_email',
            'created_at',
            'updated_at',
        ];
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $collection = collect();

        foreach ($this->_clients as $client) {
            $clientAddress = $client->address;
            $clientCity = $clientAddress ? $clientAddress->city() : '';
            $clientState = $clientAddress ? $clientAddress->state() : '';
            $clientCountry = $clientAddress ? $clientAddress->country() : '';

            $element = [
                'id' => $client->id,
                'name' => $client->name,
                'company_name' => $client->company_name,
                'legal_name' => $client->legal_name,
                'email' => $client->email,
                'birthdate' => $client->birthdate,
                'identity' => $client->identity,
                'uf' => $client->uf,
                'document' => $client->document,
                'passport' => $client->passport,
                'gender' => $client->gender,
                'language' => $client->language,
                'is_active' => $client->is_active,
                'is_valid' => $client->is_valid,
                'is_newsletter_subscriber' => $client->is_newsletter_subscriber,
                'type' => $client->type,
                'primary_document' => $client->primary_document,
                'country' => isset($clientCountry->name) ? $clientCountry->name : $clientCountry,
                'state' => isset($clientState->name) ? $clientState->name : $clientState,
                'city' =>isset($clientCity->name) ? $clientCity->name : $clientState,
                'responsible_name' => $client->responsible_name,
                'responsible_email' => $client->responsible_email,
                'created_at' => $client->created_at,
                'updated_at' => $client->updated_at,
            ];

            $collection->push($element);
        }

        return $collection;
    }
}
