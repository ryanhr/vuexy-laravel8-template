<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Permission::query()->select(sprintf('%s.*', (new Permission())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $namespace = 'admin';
                $viewGate = 'permission_show';
                $editGate = 'permission_edit';
                $deleteGate = 'permission_delete';
                $crudRoutePart = 'permissions';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'namespace',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $pageConfigs = ['pageHeader' => false];

        return view('admin.permissions.index', ['pageConfigs' => $pageConfigs]);
    }

    public function create()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pageConfigs = ['pageHeader' => false];

        return view('admin.permissions.create',['pageConfigs' => $pageConfigs]);
    }

    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->all());

        if ($permission) {
            session()->flash('success', 'Task was successful!');
        } else {
            session()->flash('error', 'Something went wrong!');
        }

        return redirect()->route('admin.permissions.index');
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pageConfigs = ['pageHeader' => false];

        return view('admin.permissions.edit', compact('permission'),['pageConfigs' => $pageConfigs]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        try {
            $permission->update($request->all());
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }
        return redirect()->route('admin.permissions.index');
    }

    public function show(Permission $permission)
    {
        abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pageConfigs = ['pageHeader' => false];

        return view('admin.permissions.show', ['pageConfigs' => $pageConfigs], compact('permission'));
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $permission->delete();
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }

        return back();
    }
}
