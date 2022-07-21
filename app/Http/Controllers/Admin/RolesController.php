<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Role::with(['permissions'])->select(sprintf('%s.*', (new Role())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $namespace = 'admin';
                $viewGate = 'role_show';
                $editGate = 'role_edit';
                $deleteGate = 'role_delete';
                $crudRoutePart = 'roles';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'namespace',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('permissions', function ($row) {
                $labels = [];
                foreach ($row->permissions as $permission) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $permission->title);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'permissions']);

            return $table->make(true);
        }

        $permissions = Permission::get();
        $pageConfigs = ['pageHeader' => false];
        return view('admin.roles.index', compact('permissions'),['pageConfigs' => $pageConfigs]);
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::pluck('title', 'id');

        $pageConfigs = ['pageHeader' => false];

        return view('admin.roles.create', compact('permissions'),['pageConfigs' => $pageConfigs]);
    }

    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());

        try {
            $role->permissions()->sync($request->input('permissions', []));
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }

        return redirect()->route('admin.roles.index');
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::pluck('title', 'id');

        $role->load('permissions');

        $pageConfigs = ['pageHeader' => false];

        return view('admin.roles.edit', compact('permissions', 'role'),['pageConfigs' => $pageConfigs]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        try {
            $role->permissions()->sync($request->input('permissions', []));
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }
        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        $pageConfigs = ['pageHeader' => false];

        return view('admin.roles.show', compact('role'),['pageConfigs' => $pageConfigs]);
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $role->delete();
            session()->flash('success', 'Task was successful!');
        } catch(Exception $exception) {
            session()->flash('error', 'Something went wrong!');
        }

        return back();
    }
}
