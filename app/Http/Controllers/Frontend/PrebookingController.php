<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\CreatePrebookingRequest;
use App\Models\Event;
use App\Repositories\CountryRepository;
use App\Repositories\PrebookingRepository;
use Exception;
use Illuminate\Http\Request;

class PrebookingController extends Controller
{
    /**
     * @var PrebookingRepository
     */
    protected $prebookingRepository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    public function __construct(PrebookingRepository $prebookingRepository, CountryRepository $countryRepository)
    {
        $this->prebookingRepository = $prebookingRepository;
        $this->countryRepository = $countryRepository;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param  Event   $event
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event, string $slug)
    {
        $countries = $this->countryRepository->list();

        return view('frontend.prebookings.create')
            ->with('event', $event)
            ->with('slug', $slug)
            ->with('countries', $countries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatePrebookingRequest  $request
     * @param  Event   $event
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePrebookingRequest $request, Event $event, string $slug)
    {
        try {
            $attributes = $request->all();
            $prebooking = $this->prebookingRepository->setEvent($event)->store($attributes);

            return redirect()->route('frontend.prebookings.create', [$event, $slug])->withSuccess(__('resources.prebookings.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('frontend.prebookings.create', [$event, $slug]);
        }
    }
}
