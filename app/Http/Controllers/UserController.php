<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $mod= $model->where('id','!=',1)->paginate(5);
        if(count($mod)!=0) {
            $currentPage = $mod->currentPage();
            foreach ($mod as $key => $each) {
                if ($currentPage !== 1)
                    $each->index = ($currentPage - 1) * 5 + $key + 1;
                else
                    $each->index = $key + 1;
            }
        }
        return view('users.index', ['users' => $mod]);
    }
    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $data = $request->merge(['password' => Hash::make($request->get('password'))])->except('assignees_roles', 'permissions');

        $roles = $request->get('roles');
        $permissions = $request->get('permissions');
        if($user=$model->create($data)){
            //Attach new roles
            $user->attachRoles($roles);

            // Attach New Permissions
            $user->attachPermissions($permissions);
        }


        return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     *
     * @param \App\Http\Requests\UserRequest $request
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws GeneralException
     */
    public function update(UserRequest $request, User  $user)
    {
        $data = $request->except('roles', 'permissions');
        $hasPassword = $request->get('password');
        $roles = $request->get('roles');
        $permissions = $request->get('permissions');
        $user->update(
            $request->merge(['password' => Hash::make($request->get('password'))])
                ->except(['roles','permissions',$hasPassword ? '' : 'password']
        ));
        $this->checkUserRolesCount($roles);
        $this->flushRoles($roles, $user);

        $this->flushPermissions($permissions, $user);

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User  $user)
    {
        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }

    /**
     * Flush roles out, then add array of new ones.
     *
     * @param $roles
     * @param $user
     */
    protected function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles);
    }

    /**
     * Flush Permissions out, then add array of new ones.
     *
     * @param $permissions
     * @param $user
     */
    protected function flushPermissions($permissions, $user)
    {
        //Flush permission out, then add array of new ones
        $user->detachPermissions($user->permissions);
        $user->attachPermissions($permissions);
    }

    /**
     * @param  $roles
     *
     * @throws GeneralException
     */
    protected function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles) == 0) {
            throw new GeneralException("Role  is needed");
        }
    }


}
