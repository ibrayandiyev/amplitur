<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;

trait ActionFilter
{
    private $_index_joins = [];
    /**
     * Filter a resource
     *
     * @param   array  $id
     *
     * @return  Model|Collection|null
     */
    public function filter(array $params, ?int $limit = null): SupportCollection
    {
        $builder = $this->model->query(); 

        foreach ($params as $param => $value) {
            if(is_array($value) &&  ( count($value) <=0 || (!isset($value[0])))){ 
                continue;
            }
            if($this->model->getIsSearchable($param) && $value != ""){
                $search = $this->model->getIndexSearchable($param);
                // this is a related search
                if(!array_key_exists($search['join_table'], $this->_index_joins)){
                    $builder = $builder->join(
                        $search['join_table'],
                        $search['join_from'], 
                        $search['join_condition'], 
                        $search['join_to']);
                    $this->_index_joins[$search['join_table']] = 1;
                }
                if(is_array($value)){
                    $builder = $builder->whereIn(
                        $search['join_field'], 
                        $value);
                }else{
                    $condition_string = str_replace("%1", $value, trim($search['join_find']));
                    $builder = $builder->where(
                        $search['join_field'], 
                        $search['join_field_ct'], 
                        $condition_string);
                }
                
                $builder = $builder->select($this->model->getTable() .".*");
                continue;
            }
            if (!in_array($param, $this->model->getFillable()) 
                    || !$value 
                    || empty($value)
                ) {
                continue;
            }
            
            $field = $this->model->getTable() .".".$param;
            if (is_array($value)) {
                $builder = $builder->whereIn($field, $value);
            } else {
                $builder = $builder->where($field, 'like', '%' . $value . '%');
            }
        }

        $builder = $this->onBeforeFilter($builder, $params);

        if (!empty($limit)) {
            $builder = $builder->limit($limit);
        }

        $resources = $builder->get();

        return $resources;
    }

    /**
     * [filterLetterStartWith description]
     *
     * @param   string             $letter  [$letter description]
     *
     * @return  SupportCollection           [return description]
     */
    public function filterLetterStartWith(string $letter, string $param = 'title'): SupportCollection
    {
        $builder = $this->model->query();

        $builder->where($param, 'like', $letter . '%');

        return $builder->get();
    }

    /**
     * Event called on before filter resource
     *
     * @param   Builder  $resource
     * @param   array  $params
     *
     * @return  Builder
     */
    public function onBeforeFilter(Builder $builder, array $params): Builder
    {
        return $builder;
    }

        /**
     * [where description]
     *
     * @param   string             $column  [$column description]
     * @param   string             $operator  [$operator description]
     * @param   string             $value  [$value description]
     * @param   string             $boolean  [$boolean description]
     *
     * @return  Model|Collection|null           [return description]
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->model = $this->model->where($column, $operator, $value, $boolean);
        return $this;

    }
}
