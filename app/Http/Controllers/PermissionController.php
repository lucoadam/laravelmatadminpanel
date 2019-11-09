<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Http\Requests\PermissionRequest;
use Illuminate\Support\Facades\Hash;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permission
     *
     * @param  \App\Permission  $model
     * @return \Illuminate\View\View
     */
    public function index(Permission $model)
    {
        return view('permission.index', ['permission' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new permission
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('permission.create');
    }

    /**
     * Store a newly created permission in storage
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @param  \App\Permission  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PermissionRequest $request, Permission $model)
    {
        $model->create($request->all());

        return redirect()->route('permission.index')->withStatus(__('Permission successfully created.'));
    }

    /**
     * Show the form for editing the specified permission
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        return view('permission.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PermissionRequest $request, Permission  $permission)
    {
        $permission->update($request->all());
        return redirect()->route('permission.index')->withStatus(__('Permission successfully updated.'));
    }

    /**
     * Remove the specified permission from storage
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Permission  $permission)
    {
        $permission->delete();

        return redirect()->route('permission.index')->withStatus(__('Permission successfully deleted.'));
    }
}
