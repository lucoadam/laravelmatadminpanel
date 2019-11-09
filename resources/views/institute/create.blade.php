@extends('layouts.app', ['activePage' => 'institute-management', 'titlePage' => __('Institute Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('institute.store') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Add Institute') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('institute.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
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
                  <label class="col-sm-2 col-form-label">{{ __('Abbr') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('abbr') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('abbr') ? ' is-invalid' : '' }}" name="abbr" id="input-abbr" type="text" placeholder="{{ __('Abbr') }}" value="{{ old('abbr') }}" required="true" aria-required="true"/>
                      @if ($errors->has('abbr'))
                        <span id="abbr-error" class="error text-danger" for="input-abbr">{{ $errors->first('abbr') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Full_abbr') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('full_abbr') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('full_abbr') ? ' is-invalid' : '' }}" name="full_abbr" id="input-full_abbr" type="text" placeholder="{{ __('Full_abbr') }}" value="{{ old('full_abbr') }}" required="true" aria-required="true"/>
                      @if ($errors->has('full_abbr'))
                        <span id="full_abbr-error" class="error text-danger" for="input-full_abbr">{{ $errors->first('full_abbr') }}</span>
                      @endif
                    </div>
                  </div>
                </div>                
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Add Institute') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection