<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\City;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;

class EventsImport implements ToCollection, WithChunkReading, ShouldQueue, WithEvents, WithStartRow
{
    use Importable;

    /**
     * @var EventRepository
     */
    protected $repository;

    protected $columns = [
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

    public function __construct()
    {
        $this->repository = app(EventRepository::class);
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $elements) {
            
            foreach ($elements as $key => $value) {
                $column = $this->columns[$key];

                // Handling translatable attributes
                if (Str::of($column)->endsWith('_ptbr')) {
                    $attributes[Str::beforeLast($column, '_ptbr')]['pt-br'] = $value;
                } else if (Str::of($column)->endsWith('_en')) {
                    $attributes[Str::beforeLast($column, '_en')]['en'] = $value;
                } else if (Str::of($column)->endsWith('_es')) {
                    $attributes[Str::beforeLast($column, '_es')]['es'] = $value;
                }

                $attributes[$column] = $value;
            }
            
            $this->createOrUpdate($attributes);
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $this->repository->importing();
                $this->repository->importFile(false);
            },

            AfterImport::class => function (AfterImport $event) {
                $this->repository->imported();
                $this->repository->importFile(false);
            },

            ImportFailed::class => function (ImportFailed $event) {
                $this->repository->imported();
                $this->repository->importFile(true);
            }
        ];
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 600;
    }

    /**
     * Creates or update an event on database
     *
     * @param   array  $attributes
     *
     * @return  void
     */
    private function createOrUpdate(array $attributes)
    {
        if (isset($attributes['id']) && !empty($attributes['id'])) {
            $event = $this->repository->find($attributes['id']);
        }

        if (!isset($attributes['name'])) {
            return;
        }

        $category = Category::where('slug->en', $attributes['category'])->first();

        $event = $event ?? new Event;
        $event->name = $attributes['name'];
        $event->city = City::find($attributes['city'])->id ?? $attributes['city'];
        $event->state = $attributes['state'];
        $event->country = $attributes['country'];
        $event->category_id = $category->id ?? null;
        $event->photo = $attributes['photo'] ?? null;
        $event->setTranslations('description', $attributes['description']);
        $event->setTranslations('meta_description', $attributes['meta_description']);
        $event->setTranslations('meta_keywords', $attributes['meta_keywords']);
        $event->save();
    }
}
