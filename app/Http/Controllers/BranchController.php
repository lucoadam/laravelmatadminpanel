<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Http\Requests\branch\BranchCreateRequest;
use App\Http\Requests\branch\BranchEditRequest;
use App\Http\Requests\branch\BranchStoreRequest;
use App\Http\Requests\branch\BranchUpdateRequest;
use App\Http\Requests\branch\BranchDeleteRequest;
use App\Http\Requests\branch\BranchViewRequest;
use Illuminate\Support\Facades\Hash;

class BranchController extends Controller
{
    /**
     * Display a listing of the branch
     *
     * @param  \App\Branch  $model
     * @return \Illuminate\View\View
     */
    public function index(BranchViewRequest $request,Branch $model)
    {
        return view('branch.index', ['branch' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new branch
     *
     * @return \Illuminate\View\View
     */
    public function create(BranchCreateRequest $request)
    {

        return view('branch.create');
    }

    /**
     * Store a newly created branch in storage
     *
     * @param  \App\Http\Requests\BranchRequest  $request
     * @param  \App\Branch  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BranchStoreRequest $request, Branch $model)
    {
        $input =$request->all();
        
        $model->create($input);
        return redirect()->route('branch.index')->withStatus(__('Branch successfully created.'));
    }

    /**
     * Show the form for editing the specified branch
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\View\View
     */
    public function edit(Branch $branch)
    {
        return view('branch.edit', compact('branch'));
    }

    /**
     * Update the specified branch in storage
     *
     * @param  \App\Http\Requests\BranchRequest  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BranchUpdateRequest $request,Branch  $branch)
    {
          $input =$request->all();
        

        $branch->update($input);
        return redirect()->route('branch.index')->withStatus(__('Branch successfully updated.'));
    }

    /**
     * Remove the specified branch from storage
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BranchDeleteRequest $request,Branch  $branch)
    {
        $branch->delete();

        return redirect()->route('branch.index')->withStatus(__('Branch successfully deleted.'));
    }
}