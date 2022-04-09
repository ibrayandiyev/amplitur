<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\EventRequest;
use App\Http\Requests\Backend\ImportEventsRequest;
use App\Models\Event;
use App\Repositories\CategoryRepository;
use App\Repositories\EventRepository;
use App\Repositories\CountryRepository;
use App\Repositories\StateRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * @var EventRepository
     */
    protected $repository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var StateRepository
     */
    protected $stateRepository;

    public function __construct(EventRepository $repository, CountryRepository $countryRepository, StateRepository $stateRepository, CategoryRepository $categoryRepository)
    {
        $this->repository = $repository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Event::class);

        try {
            return view('backend.events.index');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * [jsonFilter description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function jsonFilter(Request $request)
    {
        try {
            $events = $this->repository->filter([
                'name' => $request->input('q'),
            ], 100);
            $events->load("category");

            return response()->json($events);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);

            return response()->json([]);
        }
    }

    /**
     * [eventChangeNotification description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function eventChangeNotification(Request $request, Event $event)
    {
        try {
            $sent = $this->repository->notifyProviderEventUpdate($event);
            
            if($sent){
                return redirect()->route('backend.events.edit', $event)->withSuccess(__("backend.event.notified_provider"));
            }
            
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
        return redirect()->route('backend.events.edit', $event)->withErrors(__("backend.event.no_notify_provider"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', Event::class);

        try {
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');
            $categories = $this->categoryRepository->listEvent();

            return view('backend.events.create', compact('countries', 'states',  'categories'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.create')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $this->authorize('manage', Event::class);

        try {
            $attributes = $request->toArray();

            $event = $this->repository->store($attributes);

            return redirect()->route('backend.events.edit', $event)->withSuccess(__('resources.events.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $this->authorize('manage', Event::class);

        try {
            $address = $event->address;
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');
            $categories = $this->categoryRepository->listEvent();

            return view('backend.events.edit', compact('event', 'countries', 'states', 'address', 'categories'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EventRequest $request
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {
        $this->authorize('manage', Event::class);

        try {
            $attributes = $request->toArray();

            $event = $this->repository->update($event, $attributes);

            return redirect()->route('backend.events.edit', $event->id)->withSuccess(__('resources.events.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.edit', $event->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $this->authorize('manage', Event::class);

        try {
            $event = $this->repository->delete($event);

            return redirect()->route('backend.events.index')->withSuccess(__('resources.events.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.index')->withError($ex->getMessage());
        }
    }


    /**
     * Show the form for replicating the specified resource.
     *
     * @param  Event  $event
     * @return \Illuminate\Http\Response
     */
    public function replicate(Event $event)
    {
        $this->authorize('manage', Event::class);

        try {
            $address = $event->address;
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');
            $categories = $this->categoryRepository->listEvent();

            return view('backend.events.replicate', compact('event', 'countries', 'states', 'address', 'categories'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.index')->withError($ex->getMessage());
        }
    }

    /**
     * Filter the specified resource from storage
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $this->authorize('manage', Event::class);

        try {
            $params = $request->all();
            $events = $this->repository->filter($params);

            return view('backend.events.index', compact('events', 'params'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.index')->withError($ex->getMessage());
        }
    }

    /**
     * Export the specified resource collection from storage
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $this->authorize('manage', Event::class);

        try {
            return $this->repository->download();
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.index')->withError($ex->getMessage());
        }
    }

    /**
     * Import the specified resource collection to the storage
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $this->authorize('manage', Event::class);

        try {
            $this->repository->import($request->file('events_file'));

            return redirect()->route('backend.events.index')->withSuccess(__('resources.events.import.started'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.events.index')->withError($ex->getMessage());
        }
    }

    /**
     * [datatable description]
     *
     * @return  [type]  [return description]
     */
    public function datatable()
    {
        $response = datatables()
            ->eloquent(Event::query())
            ->setTransformer(function (Event $event) {
                return [
                    'id' => $event->id,
                    'name' => Str::upper($event->name),
                    'category_id' => Str::upper(isset($event->category) ? $event->category->getTranslation('name', app()->getLocale()) : null),
                    'country' => Str::upper(country($event->country)),
                    'state' => Str::upper(state($event->country, $event->state)),
                    'city' => Str::upper(city($event->city)),
                    'created_at' => $event->createdAtLabel,
                    'updated_at' => $event->updatedAtLabel,
                ];
            });

        return $response->toJson();
    }

    /**
     * [packages description]
     *
     * @param   Request  $request  [$request description]
     * @param   Event    $event    [$event description]
     *
     * @return  [type]             [return description]
     */
    public function packages(Request $request, Event $event)
    {
        $packages = $event->packages()->get();

        return response()->json($packages);
    }
}
