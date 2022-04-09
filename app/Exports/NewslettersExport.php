<?php

namespace App\Exports;

use App\Repositories\NewsletterRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewslettersExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'created_at',
        ];
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $repository = app(NewsletterRepository::class);

        $newsletters = $repository->list();

        $collection = collect();

        foreach ($newsletters as $newsletter) {
            $element = [
                'id' => $newsletter->id,
                'name' => $newsletter->name,
                'email' => $newsletter->email,
                'created_at' => $newsletter->created_at,
            ];

            $collection->push($element);
        }

        return $collection;
    }
}
