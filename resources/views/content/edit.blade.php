@extends('layouts.app', ['activePage' => 'content-management', 'titlePage' => __('Content Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('content.update', $content) }}" autocomplete="off" class="form-horizontal" >
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit Content') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('content.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Title') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                      <textarea rows="5" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="input-title" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required="true" aria-required="true">{{$content->title}}</textarea>
                      @if ($errors->has('title'))
                        <span id="title-error" class="error text-danger" for="input-title">{{ $errors->first('title') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Image id') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('image_id') ? ' has-danger' : '' }}">
                      @php
                    $images = \App\Models\Image::all();
                  @endphp

                              <select class="form-control{{ $errors->has('image_id') ? ' is-invalid' : '' }}" name="image_id" value="{{ $content->image_id}}">
                                  <option disabled value="">Image</option>
                                  @foreach($images as $image)
                                      <option value="{{$image->id}}">{{$image->name}}</option>
                                  @endforeach
                              </select>
                      @if ($errors->has('image_id'))
                        <span id="image_id-error" class="error text-danger" for="input-image_id">{{ $errors->first('image_id') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('File id') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('file_id') ? ' has-danger' : '' }}">
                      @php
                    $files = \App\Models\File::all();
                  @endphp

                              <select class="form-control{{ $errors->has('file_id') ? ' is-invalid' : '' }}" name="file_id" value="{{ $content->file_id}}">
                                  <option disabled value="">File</option>
                                  @foreach($files as $file)
                                      <option value="{{$file->id}}">{{$file->name}}</option>
                                  @endforeach
                              </select>
                      @if ($errors->has('file_id'))
                        <span id="file_id-error" class="error text-danger" for="input-file_id">{{ $errors->first('file_id') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text" placeholder="{{ __('Description') }}" value="{{ old('description', $content->description) }}" required="true" aria-required="true"/>
                      @if ($errors->has('description'))
                        <span id="description-error" class="error text-danger" for="input-description">{{ $errors->first('description') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Type') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" id="input-type" type="text" placeholder="{{ __('Type') }}" value="{{ old('type', $content->type) }}" required="true" aria-required="true"/>
                      @if ($errors->has('type'))
                        <span id="type-error" class="error text-danger" for="input-type">{{ $errors->first('type') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Posted by') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('posted_by') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('posted_by') ? ' is-invalid' : '' }}" name="posted_by" id="input-posted_by" type="text" placeholder="{{ __('Posted_by') }}" value="{{ old('posted_by', $content->posted_by) }}" required="true" aria-required="true"/>
                      @if ($errors->has('posted_by'))
                        <span id="posted_by-error" class="error text-danger" for="input-posted_by">{{ $errors->first('posted_by') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection