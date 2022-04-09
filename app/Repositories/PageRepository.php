<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;

class PageRepository extends Repository
{
    public function __construct(Page $model)
    {
        $this->model = $model;
    }

    /**
     * [findBySlug description]
     *
     * @param   string  $slug  [$slug description]
     *
     * @return  Page           [return description]
     */
    public function findBySlug(string $slug): ?Page
    {
        $language = language();

        if (empty($language)) {
            $language = 'pt-br';
        }

        $page = $this->model->where("slug->{$language}", $slug)->first();
        $page = $page ?? $this->model->where("slug->en", $slug)->first();
        $page = $page ?? $this->model->where("slug->es", $slug)->first();
        $page = $page ?? $this->model->where("slug->pt-br", $slug)->first();

        return $page;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        foreach ($attributes['slug'] as $language => $slug) {
            $attributes['slug'][$language] = mb_strtolower($slug);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        foreach ($attributes['slug'] as $language => $slug) {
            $attributes['slug'][$language] = mb_strtolower($slug);
        }

        return $attributes;
    }
}