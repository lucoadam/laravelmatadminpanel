<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Http\Requests\role\RoleCreateRequest;
use App\Http\Requests\role\RoleEditRequest;
use App\Http\Requests\role\RoleStoreRequest;
use App\Http\Requests\role\RoleUpdateRequest;
use App\Http\Requests\role\RoleDeleteRequest;
use App\Http\Requests\role\RoleViewRequest;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    /**
     * Display a listing of the role
     *
     * @param  \App\Role  $model
     * @return \Illuminate\View\View
     */
    public function index(RoleViewRequest $request,Role $model)
    {
        return view('role.index', ['role' => $model->paginate(20)]);
    }
    
    /**
     * Show the form for creating a new role
     *
     * @return \Illuminate\View\View
     */
    public function create(RoleCreateRequest $request)
    {
        return view('role.create');
    }

    /**
     * Store a newly created role in storage
     *
     * @param  \App\Http\Requests\RoleRequest  $request
     * @param  \App\Role  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleStoreRequest $request, Role $model)
    {
        $model->create($request->all());

        return redirect()->route('role.index')->withStatus(__('Role successfully created.'));
    }

    /**
     * Show the form for editing the specified role
     *
     * @param  \App\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified role in storage
     *
     * @param  \App\Http\Requests\RoleRequest  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleUpdateRequest $request,Role  $role)
    {
        $role->update($request->all());
        return redirect()->route('role.index')->withStatus(__('Role successfully updated.'));
    }

    /**
     * Remove the specified role from storage
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(RoleDeleteRequest $request,Role  $role)
    {
        $role->delete();

        return redirect()->route('role.index')->withStatus(__('Role successfully deleted.'));
    }
}
