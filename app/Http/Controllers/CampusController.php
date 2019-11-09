<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Http\Requests\CampusRequest;
use Illuminate\Support\Facades\Hash;

class CampusController extends Controller
{
    /**
     * Display a listing of the campus
     *
     * @param  \App\Campus  $model
     * @return \Illuminate\View\View
     */
    public function index(Campus $model)
    {
        return view('campus.index', ['campus' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new campus
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('campus.create');
    }

    /**
     * Store a newly created campus in storage
     *
     * @param  \App\Http\Requests\CampusRequest  $request
     * @param  \App\Campus  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CampusRequest $request, Campus $model)
    {
        $model->create($request->all());

        return redirect()->route('campus.index')->withStatus(__('Campus successfully created.'));
    }

    /**
     * Show the form for editing the specified campus
     *
     * @param  \App\Campus  $campus
     * @return \Illuminate\View\View
     */
    public function edit(Campus $campus)
    {
        return view('campus.edit', compact('campus'));
    }

    /**
     * Update the specified campus in storage
     *
     * @param  \App\Http\Requests\CampusRequest  $request
     * @param  \App\Campus  $campus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CampusRequest $request, Campus  $campus)
    {
        $campus->update($request->all());
        return redirect()->route('campus.index')->withStatus(__('Campus successfully updated.'));
    }

    /**
     * Remove the specified campus from storage
     *
     * @param  \App\Campus  $campus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Campus  $campus)
    {
        $campus->delete();

        return redirect()->route('campus.index')->withStatus(__('Campus successfully deleted.'));
    }
}
