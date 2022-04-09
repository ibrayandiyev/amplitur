<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Exception;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * @var PageRepository
     */
    protected $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show(Request $request, string $slug)
    {
        try {
            $page = $this->repository->findBySlug($slug);

            if (empty($page)) {
                return redirect()->route('frontend.index');
            }
            if(!$page->isActive()){
                return redirect()->route('frontend.index')->withError(__('messages.http.404'));
            }

            return view('frontend.pages.show')
                ->with('page', $page)
                ->with('slug', $slug);
            
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);

            return redirect()->route('frontend.index')->withError(__('messages.http.404'));
        }
    }
}
