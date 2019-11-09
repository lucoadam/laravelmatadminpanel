@extends('layouts.app', ['activePage' => 'product-management', 'titlePage' => __('Product Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('product.store') }}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('post')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Add Product') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('product.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Title') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="input-title" type="text" placeholder="{{ __('Title') }}" value="{{ old('title') }}" required="true" aria-required="true"/>
                      @if ($errors->has('title'))
                        <span id="title-error" class="error text-danger" for="input-title">{{ $errors->first('title') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Description') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text" placeholder="{{ __('Description') }}" value="{{ old('description') }}" required="true" aria-required="true"/>
                      @if ($errors->has('description'))
                        <span id="description-error" class="error text-danger" for="input-description">{{ $errors->first('description') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
							<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Price') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('price') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" id="input-price" type="text" placeholder="{{ __('Price') }}" value="{{ old('price') }}" required="true" aria-required="true"/>
                      @if ($errors->has('price'))
                        <span id="price-error" class="error text-danger" for="input-price">{{ $errors->first('price') }}</span>
                      @endif
                    </div>
                  </div>
                </div>                
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Add Product') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection