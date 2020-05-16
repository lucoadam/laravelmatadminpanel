@extends('layouts.app', ['activePage' => 'file-management', 'titlePage' => __('File Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('file.update', $file) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit File') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('file.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Title') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="input-title" type="text" placeholder="{{ __('Title') }}" value="{{ old('title', $file->title) }}" required="true" aria-required="true"/>
                      @if ($errors->has('title'))
                        <span id="title-error" class="error text-danger" for="input-title">{{ $errors->first('title') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('File') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('file') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" name="file" id="input-file" type="file" placeholder="{{ __('File') }}" value="{{ old('file') }}" required="true" aria-required="true"/>
                <button onclick="document.getElementById('input-file').click()" type="button" class="btn btn-fab btn-round btn-primary">
                        <i class="material-icons">attach_file</i>
                      </button>
                      @if ($errors->has('file'))
                        <span id="file-error" class="error text-danger" for="input-file">{{ $errors->first('file') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Posted by') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('posted_by') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('posted_by') ? ' is-invalid' : '' }}" name="posted_by" id="input-posted_by" type="text" placeholder="{{ __('Posted_by') }}" value="{{ old('posted_by', $file->posted_by) }}" required="true" aria-required="true"/>
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