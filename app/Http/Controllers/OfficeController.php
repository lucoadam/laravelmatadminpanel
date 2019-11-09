<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Http\Requests\OfficeRequest;
use Illuminate\Support\Facades\Hash;

class OfficeController extends Controller
{
    /**
     * Display a listing of the office
     *
     * @param  \App\Office  $model
     * @return \Illuminate\View\View
     */
    public function index(Office $model)
    {
        return view('office.index', ['office' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new office
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('office.create');
    }

    /**
     * Store a newly created office in storage
     *
     * @param  \App\Http\Requests\OfficeRequest  $request
     * @param  \App\Office  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OfficeRequest $request, Office $model)
    {
        $model->create($request->all());

        return redirect()->route('office.index')->withStatus(__('Office successfully created.'));
    }

    /**
     * Show the form for editing the specified office
     *
     * @param  \App\Office  $office
     * @return \Illuminate\View\View
     */
    public function edit(Office $office)
    {
        return view('office.edit', compact('office'));
    }

    /**
     * Update the specified office in storage
     *
     * @param  \App\Http\Requests\OfficeRequest  $request
     * @param  \App\Office  $office
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OfficeRequest $request, Office  $office)
    {
        $office->update($request->all());
        return redirect()->route('office.index')->withStatus(__('Office successfully updated.'));
    }

    /**
     * Remove the specified office from storage
     *
     * @param  \App\Office  $office
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Office  $office)
    {
        $office->delete();

        return redirect()->route('office.index')->withStatus(__('Office successfully deleted.'));
    }
}
