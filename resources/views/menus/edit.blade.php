@extends('layouts.app', ['activePage' => 'menus-management', 'titlePage' => __('Menus Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{ route('menus.update', $menu) }}" autocomplete="off" class="form-horizontal" >
            @csrf
            @method('put')

            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Edit Menus') }}</h4>
                <p class="card-category"></p>
              </div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-md-12 text-right">
                      <a href="{{ route('menus.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ old('name', $menu->name) }}" required="true" aria-required="true"/>
                      @if ($errors->has('name'))
                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
                  @php
                      $strJsonFileContents = file_get_contents(base_path('public').'/alish.json');
                        $data=json_decode($strJsonFileContents);


                  @endphp
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Icon') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('icon') ? ' has-danger' : '' }}">
                      <select class="form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}" name="icon" id="input-icon" type="text" placeholder="{{ __('Icon') }}" value="{{ old('icon', $menu->icon) }}" >
                          @foreach($data as $d)
                              <option value="{{$d->name}}"><i class="material-icons">{{$d->name}}</i></option>
                          @endforeach
                      </select>
                      @if ($errors->has('icon'))
                        <span id="icon-error" class="error text-danger" for="input-icon">{{ $errors->first('icon') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Url type') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('url_type') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('url_type') ? ' is-invalid' : '' }}" name="url_type" id="input-url_type" type="text" placeholder="{{ __('Url_type') }}" value="{{ old('url_type', $menu->url_type) }}"/>
                      @if ($errors->has('url_type'))
                        <span id="url_type-error" class="error text-danger" for="input-url_type">{{ $errors->first('url_type') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Url') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('url') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" name="url" id="input-url" type="text" placeholder="{{ __('Url') }}" value="{{ old('url', $menu->url) }}" required="true" aria-required="true"/>
                      @if ($errors->has('url'))
                        <span id="url-error" class="error text-danger" for="input-url">{{ $errors->first('url') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Parent id') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('parent_id') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('parent_id') ? ' is-invalid' : '' }}" name="parent_id" id="input-parent_id" type="number" placeholder="{{ __('Parent_id') }}" value="{{ old('parent_id') }}"/>
                      @if ($errors->has('parent_id'))
                        <span id="parent_id-error" class="error text-danger" for="input-parent_id">{{ $errors->first('parent_id') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Backend') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('backend') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('backend') ? ' is-invalid' : '' }}" name="backend" id="input-backend" type="text" placeholder="{{ __('Backend') }}" value="{{ old('backend', $menu->backend) }}" />
                      @if ($errors->has('backend'))
                        <span id="backend-error" class="error text-danger" for="input-backend">{{ $errors->first('backend') }}</span>
                      @endif
                    </div>
                  </div>
                </div>
					<div class="row">
                  <label class="col-sm-2 col-form-label">{{ __('Open in new tab') }}</label>
                  <div class="col-sm-7">
                    <div class="form-group{{ $errors->has('open_in_new_tab') ? ' has-danger' : '' }}">
                      <input class="form-control{{ $errors->has('open_in_new_tab') ? ' is-invalid' : '' }}" name="open_in_new_tab" id="input-open_in_new_tab" type="text" placeholder="{{ __('Open_in_new_tab') }}" value="{{ old('open_in_new_tab', $menu->open_in_new_tab) }}" />
                      @if ($errors->has('open_in_new_tab'))
                        <span id="open_in_new_tab-error" class="error text-danger" for="input-open_in_new_tab">{{ $errors->first('open_in_new_tab') }}</span>
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
  <script>
      const Http = new XMLHttpRequest();
      const url='{{url('/alish.json')}}';
      Http.open("GET", url);
      Http.send();

      Http.onreadystatechange = (e) => {
          console.log(Http.responseText)
      }
  </script>
@endsection
