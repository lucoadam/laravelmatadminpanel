<?php

namespace App\Http\Controllers;
use App\Models\Memory;
use App\Http\Requests\memory\MemoryCreateRequest;
use App\Http\Requests\memory\MemoryEditRequest;
use App\Http\Requests\memory\MemoryStoreRequest;
use App\Http\Requests\memory\MemoryUpdateRequest;
use App\Http\Requests\memory\MemoryDeleteRequest;
use App\Http\Requests\memory\MemoryViewRequest;
use Illuminate\Support\Facades\Hash;

class MemoryController extends Controller
{
    /**
     * Display a listing of the memory
     *
     * @param  \App\Memory  $model
     * @return \Illuminate\View\View
     */
    public function index(MemoryViewRequest $request,Memory $model)
    {
        return view('memory.index', ['memory' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new memory
     *
     * @return \Illuminate\View\View
     */
    public function create(MemoryCreateRequest $request)
    {

        return view('memory.create');
    }

    /**
     * Store a newly created memory in storage
     *
     * @param  \App\Http\Requests\MemoryRequest  $request
     * @param  \App\Memory  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MemoryStoreRequest $request, Memory $model)
    {
        $input =$request->all();
        
        $model->create($input);
        return redirect()->route('memory.index')->withStatus(__('Memory successfully created.'));
    }

    /**
     * Show the form for editing the specified memory
     *
     * @param  \App\Memory  $memory
     * @return \Illuminate\View\View
     */
    public function edit(Memory $memory)
    {
        return view('memory.edit', compact('memory'));
    }

    /**
     * Update the specified memory in storage
     *
     * @param  \App\Http\Requests\MemoryRequest  $request
     * @param  \App\Memory  $memory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MemoryUpdateRequest $request,Memory  $memory)
    {
          $input =$request->all();
        

        $memory->update($input);
        return redirect()->route('memory.index')->withStatus(__('Memory successfully updated.'));
    }

    /**
     * Remove the specified memory from storage
     *
     * @param  \App\Memory  $memory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MemoryDeleteRequest $request,Memory  $memory)
    {
        $memory->delete();

        return redirect()->route('memory.index')->withStatus(__('Memory successfully deleted.'));
    }
}