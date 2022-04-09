<?php

namespace App\Exports;

use App\Models\Category;
use App\Repositories\EventRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventsExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id',
            'name',
            'city',
            'state',
            'country',
            'category',
            'meta_description_ptbr',
            'meta_description_es',
            'meta_description_en',
            'meta_keywords_ptbr',
            'meta_keywords_es',
            'meta_keywords_en',
            'description_ptbr',
            'description_es',
            'description_en',
            'photo',
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $repository = app(EventRepository::class);

        $events = $repository->list();

        $collection = collect();

        foreach ($events as $event) {
            $category = Category::find($event->category_id);

            $element = [
                'id' => $event->id,
                'name' => $event->name,
                'city' => $event->city,
                'state' => $event->state,
                'country' => $event->country,
                'category' => !empty($category) ? $category->getTranslation('slug', 'en') : null,
                'meta_description_ptbr' => $event->getTranslation('meta_description', 'pt-br'),
                'meta_description_es' => $event->getTranslation('meta_description', 'es'),
                'meta_description_en' => $event->getTranslation('meta_description', 'en'),
                'meta_keywords_ptbr' => $event->getTranslation('meta_keywords', 'pt-br'),
                'meta_keywords_es' => $event->getTranslation('meta_keywords', 'es'),
                'meta_keywords_en' => $event->getTranslation('meta_keywords', 'en'),
                'description_ptbr' => $event->getTranslation('description', 'pt-br'),
                'description_es' => $event->getTranslation('description', 'es'),
                'description_en' => $event->getTranslation('description', 'en'),
                'photo' => $event->photo,
            ];

            $collection->push($element);
        }

        return $collection;
    }
}
