<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PermissionsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('permission_access'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new PermissionResource(Permission::advancedFilter());
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->validated());

        return (new PermissionResource($permission))
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    public function create()
    {
        abort_if(Gate::denies('permission_create'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'meta' => [],
        ]);
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('permission_show'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return new PermissionResource($permission);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());

        return (new PermissionResource($permission))
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_ACCEPTED);
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'data' => new PermissionResource($permission),
            'meta' => [],
        ]);
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();

        return response(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
