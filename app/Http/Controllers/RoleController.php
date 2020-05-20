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
        return view('role.index', ['role' => $model->all()]);
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
        $input =$request->all();
        $all = $input['all'] == '0' ? true : false;
        $input['all']=$all;
        $input['created_by']=auth()->user()->id;

        if (!isset($input['permissions'])) {
            $input['permissions'] = [];
        }
        if(!$all && count($input['permissions'])==0){
            return redirect()->back()->with([
                'all'=>'It is required'
            ]);
        }
        if($role=$model->create($input)) {
            if (!$all) {
                $permissions = [];

                if (is_array($input['permissions']) && count($input['permissions'])) {
                    foreach ($input['permissions'] as $perm) {
                        if (is_numeric($perm)) {
                            array_push($permissions, $perm);
                        }
                    }
                }

                $role->attachPermissions($permissions);
            }
        }

        //

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
        $input =$request->all();
        $all = $input['all'] == '0' ? true : false;
        $input['all']=$all;
        $input['updated_by']=auth()->user()->id;

        if (!isset($input['permissions'])) {
            $input['permissions'] = [];
        }
        if(!$all && count($input['permissions'])==0){
            return redirect()->back()->with([
                'all'=>'It is required'
            ]);
        }
        if($role->update($input)) {
            //If role has all access detach all permissions because they're not needed
            if ($all) {
                $role->permissions()->sync([]);
            } else {
                //Remove all roles first
                $role->permissions()->sync([]);

                //Attach permissions if the role does not have all access
                $permissions = [];

                if (is_array($input['permissions']) && count($input['permissions'])) {
                    foreach ($input['permissions'] as $perm) {
                        if (is_numeric($perm)) {
                            array_push($permissions, $perm);
                        }
                    }
                }

                $role->attachPermissions($permissions);
            }
        }


        $role->update($input);
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
