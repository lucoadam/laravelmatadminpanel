<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use App\Http\Requests\setting\SettingCreateRequest;
use App\Http\Requests\setting\SettingEditRequest;
use App\Http\Requests\setting\SettingStoreRequest;
use App\Http\Requests\setting\SettingUpdateRequest;
use App\Http\Requests\setting\SettingDeleteRequest;
use App\Http\Requests\setting\SettingViewRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function __construct()
    {
        DB::setDefaultConnection('mysql');
    }

    /**
     * Display a listing of the setting
     *
     * @param  \App\Setting  $model
     * @return \Illuminate\View\View
     */
    public function index(SettingViewRequest $request,Setting $model)
    {
        $model->setConnection('mysql');
        return view('setting.index', ['setting' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new setting
     *
     * @return \Illuminate\View\View
     */
    public function create(SettingCreateRequest $request)
    {

        return view('setting.create');
    }

    /**
     * Store a newly created setting in storage
     *
     * @param  \App\Http\Requests\SettingRequest  $request
     * @param  \App\Setting  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SettingStoreRequest $request, Setting $model)
    {
        $model->setConnection('mysql');
        $input =$request->all();

        $model->create($input);
        return redirect()->route('setting.index')->withStatus(__('Setting successfully created.'));
    }

    /**
     * Show the form for editing the specified setting
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\View\View
     */
    public function edit(Setting $setting)
    {
        $setting->setConnection('mysql');
        return view('setting.edit', compact('setting'));
    }

    /**
     * Update the specified setting storage
     *
     * @param  \App\Http\Requests\SettingRequest  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingUpdateRequest $request,Setting  $setting)
    {
        $setting->setConnection('mysql');
          $input =$request->all();


        $setting->update($input);
        return redirect()->route('setting.index')->withStatus(__('Setting successfully updated.'));
    }

    /**
     * Remove the specified setting from storage
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SettingDeleteRequest $request,Setting  $setting)
    {
        $setting->setConnection('mysql');
        $setting->delete();

        return redirect()->route('setting.index')->withStatus(__('Setting successfully deleted.'));
    }
}
