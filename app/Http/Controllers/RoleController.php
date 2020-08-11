<?php

namespace App\Http\Controllers;

use App\Models\Role;

use App\Http\Requests\Admin\Role\StoreRole;
use App\Http\Requests\Admin\Role\UpdateRole;
use App\Http\Requests\Admin\Role\DestroyRole;

use App\Http\Resources\Admin\Role\RoleDetail;
use App\Http\Resources\Admin\Role\RoleCollection;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }
    
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        
        return (new RoleCollection(Role::all()))->response();
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  StoreRole  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRole $request)
    {
        $role = Role::create($request->only(['name', 'slug']));
        
        $role->permissions()->sync($request->only(['permissions']));
        
        return response()->json([
            'message' => __('roles.created')
        ], 201);
    }

    /**
     * Display the specified role.
     *
     * @param  Role  $role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return (new RoleDetail($role))->response();
    }

    /**
     * Update the specified role in storage.
     *
     * @param  UpdateRole  $request
     * @param  Role  $role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRole $request, Role $role)
    {
        $role->update($request->only(['name', 'slug']));
    
        $role->permissions()->sync($request->only(['permissions']));
    
        return response()->json([
            'message' => __('roles.updated')
        ], 200);
    }
    
    /**
     * Remove the specified role from storage.
     *
     * @param DestroyRole $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function destroy(DestroyRole $request, Role $role)
    {
        $role->delete();
    
        return response()->json([
            'message' => __('roles.deleted')
        ], 200);
    }
}
