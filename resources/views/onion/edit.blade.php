@extends('layouts.app', ['activePage' => 'onion-management', 'titlePage' => __('Onion Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('onion.update', $onion) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit Onion') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('onion.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Onion') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('onion') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('onion') ? ' is-invalid' : '' }}" name="onion" id="input-onion" type="text" placeholder="{{ __('Onion') }}" value="{{ old('onion', $onion->onion) }}" required="true" aria-required="true"/>
                      @if ($errors->has('onion'))
                        <span id="onion-error" class="error text-danger" for="input-onion">{{ $errors->first('onion') }}</span>
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