<?php

namespace App\Repositories;

use App\Models\InvoiceInformation;
use Illuminate\Database\Eloquent\Collection;

class InvoiceInformationRepository extends Repository
{
    protected $type;

    public function __construct(InvoiceInformation $model)
    {
        $this->model = $model;
    }

    /**
     * List filtred if a type is defined
     *
     * @param   int|null         $paginate
     *
     * @return  Collection
     */
    public function list(?int $paginate = null, array $_params = null): Collection
    {
        $list = parent::list($paginate);

        return $list;

    }
}