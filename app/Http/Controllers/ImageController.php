<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Http\Requests\image\ImageCreateRequest;
use App\Http\Requests\image\ImageEditRequest;
use App\Http\Requests\image\ImageStoreRequest;
use App\Http\Requests\image\ImageUpdateRequest;
use App\Http\Requests\image\ImageDeleteRequest;
use App\Http\Requests\image\ImageViewRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the image
     *
     * @param  \App\Image  $model
     * @return \Illuminate\View\View
     */
    public function index(ImageViewRequest $request,Image $model)
    {
        return view('image.index', ['image' => $model->paginate(20)]);
    }

    /**
     * Show the form for creating a new image
     *
     * @return \Illuminate\View\View
     */
    public function create(ImageCreateRequest $request)
    {

        return view('image.create');
    }

    /**
     * Store a newly created image in storage
     *
     * @param  \App\Http\Requests\ImageRequest  $request
     * @param  \App\Image  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ImageStoreRequest $request, Image $model)
    {
        $input =$request->all();
        if(isset($input['image'])&&!is_null($input['image'])) {
            $inputPath=$request->image->store('public/assets/image');
            $input['image']="/".implode("storage",explode("public",$inputPath));

        }else {
            $input['image'] = '';
        }
        if(!isset($input['link'])){
            $input['link']='';
        }
        $model->create($input);
        return redirect()->route('image.index')->withStatus(__('Image successfully added.'));
    }

    /**
     * Show the form for editing the specified image
     *
     * @param  \App\Image  $image
     * @return \Illuminate\View\View
     */
    public function edit(Image $image)
    {
        return view('image.edit', compact('image'));
    }

    /**
     * Update the specified image in storage
     *
     * @param  \App\Http\Requests\ImageRequest  $request
     * @param  \App\Image  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ImageUpdateRequest $request,Image  $image)
    {
          $input =$request->all();

        if(isset($input['image'])&&!is_null($input['image'])) {
            $this->deleteOldImage($image->image);
            $inputPath=$request->image->store('public/assets/image');
            $input['image']="/".implode("storage",explode("public",$inputPath));

        }
        if(array_key_exists('link',$input)&&is_null($input['link'])){
            $input['link']='';
        }
        $image->update($input);
        return redirect()->route('image.index')->withStatus(__('Image successfully updated.'));
    }

    /**
     * Remove the specified image from storage
     *
     * @param  \App\Image  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ImageDeleteRequest $request,Image  $image)
    {
        if(isset($image->image)){
            $this->deleteOldImage($image->image);
        }
        $image->delete();

        return redirect()->route('image.index')->withStatus(__('Image successfully deleted.'));
    }

    private function deleteOldImage($image){
        if(isset($image)&&!is_null($image)){
            $image=  implode('public',explode('storage',$image));
            if(Storage::exists($image)){
                Storage::delete($image);

            }
        }
    }
}
