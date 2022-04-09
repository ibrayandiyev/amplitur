<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Repositories\ImageRepository;
use Exception;
use Illuminate\Http\Request;

class SlideshowController extends Controller
{
    /**
     * @var ImageRepository
     */
    protected $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        if (!user()->canManageSlideshow()) {
            forbidden();
        }

        try {
            $images = $this->imageRepository->getSlideshowImages();

            return view('backend.configs.slideshow.index')
                ->with('images', $images);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * [create description]
     *
     * @return  [type]  [return description]
     */
    public function create()
    {
        if (!user()->canManageSlideshow()) {
            forbidden();
        }

        try {
            return view('backend.configs.slideshow.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.slideshow.index')->withError($ex->getMessage());
        }
    }

    /**
     * [store description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function store(Request $request)
    {
        if (!user()->canManageSlideshow()) {
            forbidden();
        }

        try {
            $attributes = $request->all();
            $attributes['image'] = $request->file('image');
            $attributes['type'] = 'slideshow';

            $image = $this->imageRepository->store($attributes);

            return redirect()->route('backend.configs.slideshow.edit', $image)->withSuccess(__('resources.images.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.slideshow.create')->withError($ex->getMessage());
        }
    }

    /**
     * [edit description]
     *
     * @param   Image  $image  [$image description]
     *
     * @return  [type]         [return description]
     */
    public function edit(Image $image)
    {
        if (!user()->canManageSlideshow()) {
            forbidden();
        }

        try {
            return view('backend.configs.slideshow.edit')
                ->with('image', $image);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.slideshow.index')->withError($ex->getMessage());
        }
    }

    /**
     * [update description]
     *
     * @param   Request  $request  [$request description]
     * @param   Image    $image    [$image description]
     *
     * @return  [type]             [return description]
     */
    public function update(Request $request, Image $image)
    {
        if (!user()->canManageSlideshow()) {
            forbidden();
        }

        try {
            $attributes = $request->all();
            $attributes['image'] = $request->file('image');
            $attributes['type'] = 'slideshow';

            $image = $this->imageRepository->update($image, $attributes);

            return redirect()->route('backend.configs.slideshow.edit', $image)->withSuccess(__('resources.images.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.slideshow.edit', $image)->withError($ex->getMessage());
        }
    }

    /**
     * [destroy description]
     *
     * @param   Image  $image  [$image description]
     *
     * @return  [type]         [return description]
     */
    public function destroy(Image $image)
    {
        if (!user()->canManageSlideshow()) {
            forbidden();
        }
    
        try {
            $this->imageRepository->delete($image);

            return redirect()->route('backend.configs.slideshow.index')->withSuccess(__('resources.images.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.slideshow.index')->withError($ex->getMessage());
        }
    }
}
