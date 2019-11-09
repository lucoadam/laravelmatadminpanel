<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\MenuRequest;
use Illuminate\Support\Facades\Hash;

class MenuController extends Controller
{
    /**
     * Display a listing of the menu
     *
     * @param  \App\Menu  $model
     * @return \Illuminate\View\View
     */
    public function index(Menu $model)
    {
        return view('menu.index', ['menu' => $model->paginate(20)]);
    }
    /**
     * Show the form for creating a new menu
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('menu.create');
    }

    /**
     * Store a newly created menu in storage
     *
     * @param  \App\Http\Requests\MenuRequest  $request
     * @param  \App\Menu  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuRequest $request, Menu $model)
    {
        $model->create($request->all());

        return redirect()->route('menu.index')->withStatus(__('Menu successfully created.'));
    }

    /**
     * Show the form for editing the specified menu
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu)
    {
        return view('menu.edit', compact('menu'));
    }

    /**
     * Update the specified menu in storage
     *
     * @param  \App\Http\Requests\MenuRequest  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuRequest $request, Menu  $menu)
    {
        $menu->update($request->all());
        return redirect()->route('menu.index')->withStatus(__('Menu successfully updated.'));
    }

    /**
     * Remove the specified menu from storage
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Menu  $menu)
    {
        $menu->delete();

        return redirect()->route('menu.index')->withStatus(__('Menu successfully deleted.'));
    }
}
