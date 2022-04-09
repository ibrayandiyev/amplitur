<?php

namespace App\Repositories;

use App\Exports\NewslettersExport;
use App\Models\Newsletter;
use App\Repositories\Concerns\ActionExport;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterRepository extends Repository
{
    use ActionExport;

    public function __construct(Newsletter $model)
    {
        $this->model = $model;
    }

    /**
     * [download description]
     *
     * @return  [type]  [return description]
     */
    public function download()
    {
        $hash = time();

        return Excel::download(new NewslettersExport, "NEWSLETTER_{$hash}.csv");
    }
}