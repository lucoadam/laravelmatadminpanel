<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Http\Requests\LibraryRequest;
use Illuminate\Support\Facades\Hash;

class LibraryController extends Controller
{
    /**
     * Display a listing of the library
     *
     * @param  \App\Library  $model
     * @return \Illuminate\View\View
     */
    public function index(Library $model)
    {
        return view('library.index', ['library' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new library
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('library.create');
    }

    /**
     * Store a newly created library in storage
     *
     * @param  \App\Http\Requests\LibraryRequest  $request
     * @param  \App\Library  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LibraryRequest $request, Library $model)
    {
        $model->create($request->all());

        return redirect()->route('library.index')->withStatus(__('Library successfully created.'));
    }

    /**
     * Show the form for editing the specified library
     *
     * @param  \App\Library  $library
     * @return \Illuminate\View\View
     */
    public function edit(Library $library)
    {
        return view('library.edit', compact('library'));
    }

    /**
     * Update the specified library in storage
     *
     * @param  \App\Http\Requests\LibraryRequest  $request
     * @param  \App\Library  $library
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LibraryRequest $request, Library  $library)
    {
        $library->update($request->all());
        return redirect()->route('library.index')->withStatus(__('Library successfully updated.'));
    }

    /**
     * Remove the specified library from storage
     *
     * @param  \App\Library  $library
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Library  $library)
    {
        $library->delete();

        return redirect()->route('library.index')->withStatus(__('Library successfully deleted.'));
    }
}
