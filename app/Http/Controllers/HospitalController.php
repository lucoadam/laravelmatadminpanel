<?php

namespace App\Http\Controllers;
use App\Models\Hospital;
use App\Http\Requests\hospital\HospitalCreateRequest;
use App\Http\Requests\hospital\HospitalEditRequest;
use App\Http\Requests\hospital\HospitalStoreRequest;
use App\Http\Requests\hospital\HospitalUpdateRequest;
use App\Http\Requests\hospital\HospitalDeleteRequest;
use App\Http\Requests\hospital\HospitalViewRequest;
use Illuminate\Support\Facades\Hash;

class HospitalController extends Controller
{
    /**
     * Display a listing of the hospital
     *
     * @param  \App\Hospital  $model
     * @return \Illuminate\View\View
     */
    public function index(HospitalViewRequest $request,Hospital $model)
    {
        return view('hospital.index', ['hospital' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new hospital
     *
     * @return \Illuminate\View\View
     */
    public function create(HospitalCreateRequest $request)
    {
        return view('hospital.create');
    }

    /**
     * Store a newly created hospital in storage
     *
     * @param  \App\Http\Requests\HospitalRequest  $request
     * @param  \App\Hospital  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HospitalStoreRequest $request, Hospital $model)
    {
        $input = $request->except(['_method','_token']);
            //dd($input);
            if(isset($input['pan'])&&!is_null($input['pan'])) {
                $image= $input['pan'];
                $fileName = implode('', explode(' ', $image->getClientOriginalName()));
                $input['pan']='assets/images/'.$fileName;
                $request->pan->store($input['pan']);
                $input['pan']='/storage/'.$input['pan'];
            }else {
                $input['pan'] = '';
            }
            //dd($input);
        $model->create($input);

        return redirect()->route('hospital.index')->withStatus(__('Hospital successfully created.'));
    }

    /**
     * Show the form for editing the specified hospital
     *
     * @param  \App\Hospital  $hospital
     * @return \Illuminate\View\View
     */
    public function edit(Hospital $hospital)
    {
        return view('hospital.edit', compact('hospital'));
    }

    /**
     * Update the specified hospital in storage
     *
     * @param  \App\Http\Requests\HospitalRequest  $request
     * @param  \App\Hospital  $hospital
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(HospitalUpdateRequest $request,Hospital  $hospital)
    {
        $hospital->update($request->all());
        return redirect()->route('hospital.index')->withStatus(__('Hospital successfully updated.'));
    }

    /**
     * Remove the specified hospital from storage
     *
     * @param  \App\Hospital  $hospital
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(HospitalDeleteRequest $request,Hospital  $hospital)
    {
        $hospital->delete();

        return redirect()->route('hospital.index')->withStatus(__('Hospital successfully deleted.'));
    }
}