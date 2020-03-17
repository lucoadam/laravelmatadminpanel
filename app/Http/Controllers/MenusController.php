<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use App\Models\Menus;
use App\Http\Requests\menus\MenusCreateRequest;
use App\Http\Requests\menus\MenusEditRequest;
use App\Http\Requests\menus\MenusStoreRequest;
use App\Http\Requests\menus\MenusUpdateRequest;
use App\Http\Requests\menus\MenusDeleteRequest;
use App\Http\Requests\menus\MenusViewRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MenusController extends Controller
{
    /**
     * Display a listing of the menus
     *
     * @param  \App\Menus  $model
     * @return \Illuminate\View\View
     */
    public function index(MenusViewRequest $request,Menu $model)
    {
        return view('menus.index', ['menus' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new menus
     *
     * @return \Illuminate\View\View
     */
    public function create(MenusCreateRequest $request)
    {

        return view('menus.create');
    }

    /**
     * Store a newly created menus in storage
     *
     * @param  \App\Http\Requests\MenusRequest  $request
     * @param  \App\Menus  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenusStoreRequest $request, Menu $model)
    {
        $input =$request->all();

        $model->create($input);
        return redirect()->route('menus.index')->withStatus(__('Menus successfully created.'));
    }

    /**
     * Show the form for editing the specified menus
     *
     * @param  \App\Menus  $menu
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu)
    {

        return view('menus.edit', compact('menu'));
    }

    /**
     * Update the specified menus in storage
     *
     * @param  \App\Http\Requests\MenusRequest  $request
     * @param  \App\Menus  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenusUpdateRequest $request,Menu  $menu)
    {
          $input =$request->all();


        $menu->update($input);
        return redirect()->route('menus.index')->withStatus(__('Menus successfully updated.'));
    }

    /**
     * Remove the specified menus from storage
     *
     * @param  \App\Menus  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MenusDeleteRequest $request,Menu  $menu)
    {
        $menu->delete();

        return redirect()->route('menus.index')->withStatus(__('Menus successfully deleted.'));
    }
}
