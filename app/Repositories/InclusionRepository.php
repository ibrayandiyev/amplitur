<?php

namespace App\Repositories;

use App\Models\Inclusion;
use Illuminate\Database\Eloquent\Collection;

class InclusionRepository extends Repository
{
    protected $type;

    public function __construct(Inclusion $model)
    {
        $this->model = $model;
    }

    public function setType(string $type): InclusionRepository
    {
        $this->type = $type;

        return $this;
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

        if (empty($this->type)) {
            return $list;
        }

        return $list->where('type', $this->type);
    }
}