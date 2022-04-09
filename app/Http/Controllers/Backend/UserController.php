<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\StateRepository;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepository
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

    public function __construct(UserRepository $repository)
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
        $this->authorize('manage', User::class);

        try {
            $users = $this->repository->list();

            return view('backend.users.index')
                ->with('users', $users);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', User::class);

        try {
            return view('backend.users.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.users.create')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('manage', User::class);

        try {
            $attributes = $request->toArray();

            $user = $this->repository->store($attributes);

            return redirect()->route('backend.configs.users.edit', $user)->withSuccess(__('resources.users.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.users.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('manage', User::class);

        try {
            return view('backend.users.edit')
                ->with('user', $user);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.users.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('manage', User::class);

        try {
            $attributes = $request->toArray();

            $user = $this->repository->update($user, $attributes);

            return redirect()->route('backend.configs.users.edit', $user)->withSuccess(__('resources.events.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.users.edit', $user)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('manage', User::class);

        try {
            $user = $this->repository->delete($user);

            return redirect()->route('backend.configs.users.index')->withSuccess(__('resources.users.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.users.index')->withError($ex->getMessage());
        }
    }
}
