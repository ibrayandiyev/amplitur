<?php

namespace App\Repositories\Concerns;

use App\Exports\EventsExport;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

trait ActionExport
{
    /**
     * Get exportable list of the resource
     *
     * @param array|null  $where
     * @param string      $orderBy
     * @param string      $orderDir
     * 
     * @return Collection
     */
    public function export(?array $where = null, string $orderBy = 'created_at', string $orderDir = 'asc')
    {
        //
    }

    /**
     * Get array of columns to be exported
     * 
     * @return array
     */
    public function getExportColumns(): array
    {
        if(!$this->exportColumns) {
            return ['*'];
        }

        return $this->exportColumns;
    }

    public function getExportableCollection(?array $where = null, string $orderBy = 'created_at', string $orderDir = 'asc'): Collection
    {
        $query = $this->model->newQuery();

        if ($where && count($where) > 0) {
            $query = $query->where($where);
        }

        $collection = $query->select($this->getExportColumns())
            ->orderBy($orderBy, $orderDir)
            ->get();

        $collection = $this->onBeforeExport($collection);

        return $collection;
    }

    /**
     * Event called on before export
     *
     * @param   Collection  $collection
     * 
     * @return  Collection
     */
    public function onBeforeExport(Collection $collection): Collection
    {
        return $collection;
    }
}
