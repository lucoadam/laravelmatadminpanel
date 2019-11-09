<?php

namespace App\Http\Controllers;

use App\Models\Onion;
use App\Http\Requests\OnionRequest;
use Illuminate\Support\Facades\Hash;

class OnionController extends Controller
{
    /**
     * Display a listing of the onion
     *
     * @param  \App\Onion  $model
     * @return \Illuminate\View\View
     */
    public function index(Onion $model)
    {
        return view('onion.index', ['onion' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new onion
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('onion.create');
    }

    /**
     * Store a newly created onion in storage
     *
     * @param  \App\Http\Requests\OnionRequest  $request
     * @param  \App\Onion  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OnionRequest $request, Onion $model)
    {
        $model->create($request->all());

        return redirect()->route('onion.index')->withStatus(__('Onion successfully created.'));
    }

    /**
     * Show the form for editing the specified onion
     *
     * @param  \App\Onion  $onion
     * @return \Illuminate\View\View
     */
    public function edit(Onion $onion)
    {
        return view('onion.edit', compact('onion'));
    }

    /**
     * Update the specified onion in storage
     *
     * @param  \App\Http\Requests\OnionRequest  $request
     * @param  \App\Onion  $onion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(OnionRequest $request, Onion  $onion)
    {
        $onion->update($request->all());
        return redirect()->route('onion.index')->withStatus(__('Onion successfully updated.'));
    }

    /**
     * Remove the specified onion from storage
     *
     * @param  \App\Onion  $onion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Onion  $onion)
    {
        $onion->delete();

        return redirect()->route('onion.index')->withStatus(__('Onion successfully deleted.'));
    }
}
