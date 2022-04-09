<?php

namespace App\Repositories;

use App\Models\Exclusion;
use Illuminate\Database\Eloquent\Collection;

class ExclusionRepository extends Repository
{
    protected $type;

    public function __construct(Exclusion $model)
    {
        $this->model = $model;
    }

    public function setType(string $type): ExclusionRepository
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