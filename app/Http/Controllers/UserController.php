<?php

namespace App\Http\Controllers;

use App\User;
use App\Exports\UserExport;

use App\Http\Resources\Admin\User\UserDetail;
use App\Http\Resources\Admin\User\UserCollection;

use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the users.
     *
     * @param IndexUser $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexUser $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::query();

        $users->when($request->keyword, function ($q, $keyword) {
            return $q->where('name', 'LIKE', "{$keyword}%");
        });

        $users->when($request->filter, function ($q, $filter) {
            $filter = json_decode($filter);

            if (!empty($filter->name) && !empty($filter->type)) {
                return $q->orderBy($filter->name, $filter->type);
            }

            return $q;
        });

        $users = $users->paginate();

        return (new UserCollection($users))->response();
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  StoreUser  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUser $request)
    {
        User::create($request->validated());

        return response()->json([
            'message' => __('users.created')
        ], 201);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return (new UserDetail($user))->response();
    }

    /**
     * Update the specified user in storage.
     *
     * @param  UpdateUser  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->update($request->validated());

        return response()->json([
            'message' => __('users.updated')
        ], 200);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => __('users.deleted')
        ], 200);
    }

    /***
     * Export the users in Excel format
     *
     * @return UserExport
     */
    public function export()
    {
        return new UserExport();
    }
}
