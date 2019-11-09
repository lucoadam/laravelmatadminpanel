<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\StaffRequest;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff
     *
     * @param  \App\Staff  $model
     * @return \Illuminate\View\View
     */
    public function index(Staff $model)
    {
        return view('staff.index', ['staff' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new staff
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created staff in storage
     *
     * @param  \App\Http\Requests\StaffRequest  $request
     * @param  \App\Staff  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StaffRequest $request, Staff $model)
    {
        $model->create($request->all());

        return redirect()->route('staff.index')->withStatus(__('Staff successfully created.'));
    }

    /**
     * Show the form for editing the specified staff
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\View\View
     */
    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff in storage
     *
     * @param  \App\Http\Requests\StaffRequest  $request
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StaffRequest $request, Staff  $staff)
    {
        $staff->update($request->all());
        return redirect()->route('staff.index')->withStatus(__('Staff successfully updated.'));
    }

    /**
     * Remove the specified staff from storage
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Staff  $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')->withStatus(__('Staff successfully deleted.'));
    }
}
