<?php

namespace App\Http\Controllers;
use App\Models\File;
use App\Http\Requests\file\FileCreateRequest;
use App\Http\Requests\file\FileEditRequest;
use App\Http\Requests\file\FileStoreRequest;
use App\Http\Requests\file\FileUpdateRequest;
use App\Http\Requests\file\FileDeleteRequest;
use App\Http\Requests\file\FileViewRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the file
     *
     * @param  \App\File  $model
     * @return \Illuminate\View\View
     */
    public function index(FileViewRequest $request,File $model)
    {
        return view('file.index', ['file' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new file
     *
     * @return \Illuminate\View\View
     */
    public function create(FileCreateRequest $request)
    {

        return view('file.create');
    }

    /**
     * Store a newly created file in storage
     *
     * @param  \App\Http\Requests\FileRequest  $request
     * @param  \App\File  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FileStoreRequest $request, File $model)
    {
        $input =$request->all();

	if(isset($input['file'])&&!is_null($input['file'])) {
            $inputfilePath=$request->file->store('public/assets/file');
            $input['file']="/".implode("storage",explode("public",$inputfilePath));

        }else {
            $input['file'] = '';
        }
        $model->create($input);
        return redirect()->route('file.index')->withStatus(__('File successfully created.'));
    }

    /**
     * Show the form for editing the specified file
     *
     * @param  \App\File  $file
     * @return \Illuminate\View\View
     */
    public function edit(File $file)
    {
        return view('file.edit', compact('file'));
    }

    /**
     * Update the specified file in storage
     *
     * @param  \App\Http\Requests\FileRequest  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FileUpdateRequest $request,File  $file)
    {
          $input =$request->all();

	    if(isset($input['file'])&&!is_null($input['file'])) {
	        $this->deleteOldfile($file->file);
            $inputfilePath=$request->file->store('public/assets/file');
            $input['file']="/".implode("storage",explode("public",$inputfilePath));

        }

        $file->update($input);
        return redirect()->route('file.index')->withStatus(__('File successfully updated.'));
    }

    /**
     * Remove the specified file from storage
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FileDeleteRequest $request,File  $file)
    {
        if(isset($file->file)){
            $this->deleteOldfile($file->file);
        }
        $file->delete();

        return redirect()->route('file.index')->withStatus(__('File successfully deleted.'));
    }
    private function deleteOldfile($file){
        if(isset($file)&&!is_null($file)){
            $file=  implode('public',explode('storage',$file));
            if(Storage::exists($file)){
                Storage::delete($file);

            }
        }
    }
}
