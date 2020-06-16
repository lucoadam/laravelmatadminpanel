<?php

namespace App\Http\Controllers;
use App\Models\Permission;
use App\Http\Requests\permission\PermissionCreateRequest;
use App\Http\Requests\permission\PermissionEditRequest;
use App\Http\Requests\permission\PermissionStoreRequest;
use App\Http\Requests\permission\PermissionUpdateRequest;
use App\Http\Requests\permission\PermissionDeleteRequest;
use App\Http\Requests\permission\PermissionViewRequest;
use Illuminate\Support\Facades\Hash;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permission
     *
     * @param  \App\Permission  $model
     * @return \Illuminate\View\View
     */
    public function index(PermissionViewRequest $request,Permission $model)
    {
        $mod=$model->all();
        foreach($mod as $k=>$m){
            $m->index=$k+1;
        }
        return view('permission.index', ['permission' => $mod]);
    }

    /**
     * Show the form for creating a new permission
     *
     * @return \Illuminate\View\View
     */
    public function create(PermissionCreateRequest $request)
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
    public function store(PermissionStoreRequest $request, Permission $model)
    {
        $input =$request->all();
        $input['status']=1;
        $input['created_by']=auth()->user()->id;
        $model->create($input);
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
    public function update(PermissionUpdateRequest $request,Permission  $permission)
    {
        $input =$request->all();

        $permission->update($input);
        return redirect()->route('permission.index')->withStatus(__('Permission successfully updated.'));
    }

    /**
     * Remove the specified permission from storage
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PermissionDeleteRequest $request,Permission  $permission)
    {
        $permission->delete();

        return redirect()->route('permission.index')->withStatus(__('Permission successfully deleted.'));
    }
}
