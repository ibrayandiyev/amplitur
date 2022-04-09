<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Newsletters\NewsletterStoreRequest;
use App\Repositories\NewsletterRepository;
use Illuminate\Http\Request;

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
     * [store description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function store(NewsletterStoreRequest $request)
    {
        try {
            $attributes = $request->all();

            $this->repository->store($attributes);

            return redirect()->route('frontend.index')->withSuccess(__('frontend.forms.newsletter_success'));
        } catch (\Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('frontend.index')->withError(__('frontend.forms.newsletter_fail'));
        }
    }

    /**
     * [success description]
     *
     * @return  [type]  [return description]
     */
    public function success()
    {
        return view('frontend.newsletter.success');
    }
}
