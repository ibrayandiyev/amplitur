<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\EventRepository;
use App\Repositories\ImageRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PageGroupRepository;

class HomeController extends Controller
{
    /**
     * @var PackageRepository
     */
    protected $packageRepository;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var PageGroupRepository
     */
    protected $pageGroupsRepository;

    /**
     * @var ImageRepository
     */
    protected $imageRepository;

    public function __construct(
        PackageRepository $packageRepository,
        EventRepository $eventRepository,
        PageGroupRepository $pageGroupsRepository,
        ImageRepository $imageRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->eventRepository = $eventRepository;
        $this->pageGroupsRepository = $pageGroupsRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages           = $this->packageRepository->listActive();
        $prebookingEvents   = $this->eventRepository->listOnPrebooking();
        $nextPackages       = $this->packageRepository->listNextPackages();
        $topPackages        = $this->packageRepository->listTopPackages();
        $slideshowImages    = $this->imageRepository->getSlideshowImages(language());

        return view('frontend.index')
            ->with('nextPackages', $nextPackages)
            ->with('topPackages', $topPackages)
            ->with('prebookingEvents', $prebookingEvents)
            ->with('packages', $packages)
            ->with('slideshowImages', $slideshowImages);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_api()
    {
        return null;
    }
}
