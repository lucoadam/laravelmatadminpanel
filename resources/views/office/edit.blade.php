@extends('layouts.app', ['activePage' => 'office-management', 'titlePage' => __('Office Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('office.update', $office) }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit Office') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('office.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $office->name) }}" required="true" aria-required="true"/>
                      @if ($errors->has('name'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Location') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('location') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" id="input-location" type="text" placeholder="{{ __('Location') }}" value="{{ old('location', $office->location) }}" required="true" aria-required="true"/>
                      @if ($errors->has('location'))
                        <span id="location-error" class="error text-danger" for="input-location">{{ $errors->first('location') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Staff_no') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('staff_no') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('staff_no') ? ' is-invalid' : '' }}" name="staff_no" id="input-staff_no" type="text" placeholder="{{ __('Staff_no') }}" value="{{ old('staff_no', $office->staff_no) }}" required="true" aria-required="true"/>
                      @if ($errors->has('staff_no'))
                        <span id="staff_no-error" class="error text-danger" for="input-staff_no">{{ $errors->first('staff_no') }}</span>
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