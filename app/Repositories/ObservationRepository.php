<?php

namespace App\Repositories;

use App\Models\Observation;
use Illuminate\Database\Eloquent\Collection;

class ObservationRepository extends Repository
{
    protected $type;

    public function __construct(Observation $model)
    {
        $this->model = $model;
    }

    /**
     * Set resource type
     *
     * @param   string  $type
     *
     * @return  ObservationRepository
     */
    public function setType(string $type): ObservationRepository
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