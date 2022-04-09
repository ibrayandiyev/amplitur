<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Repositories\NewsletterRepository;

class NewsletterController extends Controller
{
    /**
     * @var NewsletterRepository
     */
    protected $repository;

    public function __construct(NewsletterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        $this->authorize('manage', Newsletter::class);

        try {
            $newsletters = $this->repository->list();

            return view('backend.reports.newsletters.index')
                ->with('newsletters', $newsletters);
        } catch (\Exception $ex) {
           bugtracker()->notifyException($ex);
           return view('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * [export description]
     *
     * @return  [type]  [return description]
     */
    public function export()
    {
        $this->authorize('manage', Newsletter::class);

        try {
            return $this->repository->download();
        } catch (\Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.reports.newsletters.index')->withError($ex->getMessage());
        }
    }
}
