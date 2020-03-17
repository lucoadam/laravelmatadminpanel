@extends('layouts.app', ['activePage' => 'setting-management', 'titlePage' => __('Setting Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('setting.update', $setting) }}" autocomplete="off" class="form-horizontal" >
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit Setting') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('setting.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Key') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('key') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('key') ? ' is-invalid' : '' }}" name="key" id="input-key" type="text" placeholder="{{ __('Key') }}" value="{{ old('key', $setting->key) }}" required="true" aria-required="true"/>
                      @if ($errors->has('key'))
                        <span id="key-error" class="error text-danger" for="input-key">{{ $errors->first('key') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Value') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('value') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" name="value" id="input-value" type="text" placeholder="{{ __('Value') }}" value="{{ old('value', $setting->value) }}" required="true" aria-required="true"/>
                      @if ($errors->has('value'))
                        <span id="value-error" class="error text-danger" for="input-value">{{ $errors->first('value') }}</span>
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