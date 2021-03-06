<?php

namespace App\Http\Controllers\Api\System;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\System\Role;
use App\Helpers\Response;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $search = $request->query('search');
        $query = Role::query();
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', '%' . $search . '%');
                $query->orWhere('code', 'ilike', '%' . $search . '%');
            });
        }

        return RoleResource::collection($query->with('actions:id,name')->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        Role::create($request->all());
        return Response::created();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\System\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->all());
        return Response::updated();
    }
    public function updateActions(Request $request, Role $role)
    {
        $role->actions()->sync($request->actions);
        return Response::updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\System\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        return Helper::delete($role);
    }

    public function adjustActions(Request $request, Role $role)
    {
        if (!is_array($request->actions)) return Response::error('D??? li???u kh??ng h???p l???');
        $role->actions()->attach($request->actions);
        return Response::updated();
    }
}
