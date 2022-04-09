<?php

namespace App\Services\PaymentGateway;

use InvalidArgumentException;

class BilletPayee
{
    public $name;
    public $address;
    public $zip;
    public $uf;
    public $city;
    public $document;

    public function __construct(string $name, string $address, string $zip, string $uf, string $city, string $document)
    {
        $this->name = $name;
        $this->address = $address;
        $this->zip = $zip;
        $this->uf = $uf;
        $this->city = $city;
        $this->document = $document;
    }

    /**
     * [toArray description]
     *
     * @return  array   [return description]
     */
    public function toArray(): array
    {
        return [
            'nome' => $this->name,
            'endereco' => $this->address,
            'cep' => $this->zip,
            'uf' => $this->uf,
            'cidade' => $this->city,
            'document' => $this->document,
        ];
    }

    /**
     * [isValid description]
     *
     * @return  bool    [return description]
     */
    public function isValid(): bool
    {
        if (empty($this->name) || empty($this->address) || empty($this->zip) || empty($this->uf) || empty($this->city) || empty($this->document)) {
            throw new InvalidArgumentException;
            return false;
        }

        return true;
    }
}