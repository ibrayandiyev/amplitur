<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Repositories\ImageRepository;
use Exception;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * @var ImageRepository
     */
    protected $repository;

    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Image::class);

        try {
            $images = $this->repository->listPackagesAndEvents();

            return view('backend.images.index')->with('images', $images);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withErrors($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', Image::class);

        try {
            return view('backend.images.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.images.create')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', Image::class);

        try {
            $attributes = $request->toArray();
            $attributes['image'] = $request->file('image');

            $image = $this->repository->store($attributes);

            return redirect()->route('backend.images.edit', $image)->withSuccess(__('resources.images.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.images.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        $this->authorize('manage', Image::class);

        try {
            return view('backend.images.edit')->with('image', $image);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.images.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        $this->authorize('manage', Image::class);

        try {
            $attributes = $request->toArray();
            $attributes['image'] = $request->file('image');

            $image = $this->repository->update($image, $attributes);

            return redirect()->route('backend.images.edit', $image)->withSuccess(__('resources.images.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.images.edit', $image)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $this->authorize('manage', Image::class);

        try {
            $image = $this->repository->delete($image);

            return redirect()->route('backend.images.index')->withSuccess(__('resources.images.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.images.index')->withError($ex->getMessage());
        }
    }

    public function filter(Request $request)
    {
        $this->authorize('manage', Image::class);

        try {
            $letter = $request->get('letter');
            $images = $this->repository->filterLetterStartWith($letter);

            return view('backend.images.index')
                ->with('letter', $letter)
                ->with('images', $images);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.images.index')->withError($ex->getMessage());
        }
    }
}
