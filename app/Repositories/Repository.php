<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Concerns\ActionList;
use App\Repositories\Concerns\ActionFind;
use App\Repositories\Concerns\ActionStore;
use App\Repositories\Concerns\ActionUpdate;
use App\Repositories\Concerns\ActionDelete;
use App\Repositories\Concerns\ActionErrors;
use App\Repositories\Concerns\ActionFilter;
use App\Repositories\Concerns\ActionFindBy;
use App\Repositories\Concerns\ActionSuccess;

abstract class Repository
{
    use ActionErrors,
        ActionList,
        ActionFind,
        ActionFindBy,
        ActionStore,
        ActionUpdate,
        ActionDelete,
        ActionFilter,
        ActionSuccess;

    /**
     * Repositories model
     * @var Model
     */
    protected $model;

    /**
     * Model resource
     * @var Model
     */
    protected $resource;

    /**
     * Model attributes
     * @var array
     */
    protected $attributes;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel(){
        return $this->model;
    }
}
