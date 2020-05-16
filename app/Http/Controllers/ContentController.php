<?php

namespace App\Http\Controllers;
use App\Models\Content;
use App\Http\Requests\content\ContentCreateRequest;
use App\Http\Requests\content\ContentEditRequest;
use App\Http\Requests\content\ContentStoreRequest;
use App\Http\Requests\content\ContentUpdateRequest;
use App\Http\Requests\content\ContentDeleteRequest;
use App\Http\Requests\content\ContentViewRequest;
use Illuminate\Support\Facades\Hash;

class ContentController extends Controller
{
    /**
     * Display a listing of the content
     *
     * @param  \App\Content  $model
     * @return \Illuminate\View\View
     */
    public function index(ContentViewRequest $request,Content $model)
    {
        return view('content.index', ['content' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new content
     *
     * @return \Illuminate\View\View
     */
    public function create(ContentCreateRequest $request)
    {

        return view('content.create');
    }

    /**
     * Store a newly created content in storage
     *
     * @param  \App\Http\Requests\ContentRequest  $request
     * @param  \App\Content  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContentStoreRequest $request, Content $model)
    {
        $input =$request->all();
        
        $model->create($input);
        return redirect()->route('content.index')->withStatus(__('Content successfully created.'));
    }

    /**
     * Show the form for editing the specified content
     *
     * @param  \App\Content  $content
     * @return \Illuminate\View\View
     */
    public function edit(Content $content)
    {
        return view('content.edit', compact('content'));
    }

    /**
     * Update the specified content in storage
     *
     * @param  \App\Http\Requests\ContentRequest  $request
     * @param  \App\Content  $content
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ContentUpdateRequest $request,Content  $content)
    {
          $input =$request->all();
        

        $content->update($input);
        return redirect()->route('content.index')->withStatus(__('Content successfully updated.'));
    }

    /**
     * Remove the specified content from storage
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ContentDeleteRequest $request,Content  $content)
    {
        $content->delete();

        return redirect()->route('content.index')->withStatus(__('Content successfully deleted.'));
    }
}