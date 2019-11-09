<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use App\Http\Requests\InstituteRequest;
use Illuminate\Support\Facades\Hash;

class InstituteController extends Controller
{
    /**
     * Display a listing of the institute
     *
     * @param  \App\Institute  $model
     * @return \Illuminate\View\View
     */
    public function index(Institute $model)
    {
        return view('institute.index', ['institute' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new institute
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('institute.create');
    }

    /**
     * Store a newly created institute in storage
     *
     * @param  \App\Http\Requests\InstituteRequest  $request
     * @param  \App\Institute  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(InstituteRequest $request, Institute $model)
    {
        $model->create($request->all());

        return redirect()->route('institute.index')->withStatus(__('Institute successfully created.'));
    }

    /**
     * Show the form for editing the specified institute
     *
     * @param  \App\Institute  $institute
     * @return \Illuminate\View\View
     */
    public function edit(Institute $institute)
    {
        return view('institute.edit', compact('institute'));
    }

    /**
     * Update the specified institute in storage
     *
     * @param  \App\Http\Requests\InstituteRequest  $request
     * @param  \App\Institute  $institute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(InstituteRequest $request, Institute  $institute)
    {
        $institute->update($request->all());
        return redirect()->route('institute.index')->withStatus(__('Institute successfully updated.'));
    }

    /**
     * Remove the specified institute from storage
     *
     * @param  \App\Institute  $institute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Institute  $institute)
    {
        $institute->delete();

        return redirect()->route('institute.index')->withStatus(__('Institute successfully deleted.'));
    }
}
