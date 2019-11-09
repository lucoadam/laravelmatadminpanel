@extends('layouts.app', ['activePage' => 'library-management', 'titlePage' => __('Library Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('library.store') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Add Library') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('library.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required="true" aria-required="true"/>
                      @if ($errors->has('name'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Content') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('content') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" id="input-content" type="text" placeholder="{{ __('Content') }}" value="{{ old('content') }}" required="true" aria-required="true"/>
                      @if ($errors->has('content'))
                        <span id="content-error" class="error text-danger" for="input-content">{{ $errors->first('content') }}</span>
                      @endif
                    </div>
                  </div>
                </div>                
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Add Library') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection