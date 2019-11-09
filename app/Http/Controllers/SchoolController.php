<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Http\Requests\SchoolRequest;
use Illuminate\Support\Facades\Hash;

class SchoolController extends Controller
{
    /**
     * Display a listing of the school
     *
     * @param  \App\School  $model
     * @return \Illuminate\View\View
     */
    public function index(School $model)
    {
        return view('school.index', ['school' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new school
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('school.create');
    }

    /**
     * Store a newly created school in storage
     *
     * @param  \App\Http\Requests\SchoolRequest  $request
     * @param  \App\School  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SchoolRequest $request, School $model)
    {
        $model->create($request->all());

        return redirect()->route('school.index')->withStatus(__('School successfully created.'));
    }

    /**
     * Show the form for editing the specified school
     *
     * @param  \App\School  $school
     * @return \Illuminate\View\View
     */
    public function edit(School $school)
    {
        return view('school.edit', compact('school'));
    }

    /**
     * Update the specified school in storage
     *
     * @param  \App\Http\Requests\SchoolRequest  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SchoolRequest $request, School  $school)
    {
        $school->update($request->all());
        return redirect()->route('school.index')->withStatus(__('School successfully updated.'));
    }

    /**
     * Remove the specified school from storage
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(School  $school)
    {
        $school->delete();

        return redirect()->route('school.index')->withStatus(__('School successfully deleted.'));
    }
}
