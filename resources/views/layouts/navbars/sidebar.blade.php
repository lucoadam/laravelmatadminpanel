<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="/" class="simple-text logo-normal">
      {{ env('APP_NAME','Admin Panel') }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item {{ ($activePage == 'profile' || $activePage == 'user-management') ? ' active' : '' }}">
        <a class="nav-link {{ ($activePage == 'profile' || $activePage == 'user-management') ? '' : 'collapsed' }}" data-toggle="collapse" href="#laravelExample" aria-expanded="{{ ($activePage == 'profile' || $activePage == 'user-management') ? 'true' : 'false' }}">
{{--          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>--}}
          <p>{{ __('User Management') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'profile' || $activePage == 'user-management') ? 'show' : '' }}" id="laravelExample">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('profile.edit') }}">
                <span class="sidebar-mini"> UP </span>
                <span class="sidebar-normal">{{ __('User profile') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('user.index') }}">
                <span class="sidebar-mini"> UM </span>
                <span class="sidebar-normal"> {{ __('User Management') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ ($activePage == 'department-management') ? ' active' : '' }}">
        <a class="nav-link {{ ($activePage == 'department-management') ? '' : 'collapsed' }}" data-toggle="collapse" href="#settingManagement" aria-expanded="{{ ($activePage == 'profile' || $activePage == 'user-management') ? 'true' : 'false' }}">
          {{--          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>--}}
          <p>{{ __('Setting Management') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'profile' || $activePage == 'department-management') ? 'show' : '' }}" id="settingManagement">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'department-management' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('settings.department.index') }}">
                <span class="sidebar-mini"> UP </span>
                <span class="sidebar-normal">{{ __('Department Management') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      @php
          $parent= \App\Models\Menu::select(['id','name','url'])->orderBy('name')->where('parent_id',0)->get();
     foreach($parent as $p){
         $child =\App\Models\Menu::select(['id','name','url'])->orderBy('name')->where('parent_id',$p->id);
         if($child->exists()){
             $p['children']=$child->get();
         }

     }
      @endphp
      @foreach($parent as $par)
            <li class="nav-item{{ $activePage == strtolower($par->name).'-management' ? ' active' : '' }}" >
                <a class="nav-link" href="{{$par->url!='#'?route($par->url):$par->url.strtolower($par->name).'Management'}}" {!! $par->url=='#'?'data-toggle="collapse"':''!!}>
                    <i class="material-icons">content_paste</i>
                    <p>{{ $par->name }}</p>
                </a>
                @if($par->url=='#')
                    <div class="collapse {{ (0) ? 'show' : '' }}" id="{{strtolower($par->name).'Management'}}">
                        <ul class="nav">
                            @foreach($par->children as $child)
                            <li class="nav-item{{ $activePage == strtolower($child->name).'-management' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route($child->url) }}">
                                    <span class="sidebar-mini"> UP </span>
                                    <span class="sidebar-normal">{{ $child->name.' Management' }} </span>
                                </a>
                            </li>
                                @endforeach
                        </ul>
                    </div>
                @endif
            </li>
      @endforeach
      <li class="nav-item{{ $activePage == 'table' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('table') }}">
          <i class="material-icons">content_paste</i>
            <p>{{ __('Table List') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('typography') }}">
          <i class="material-icons">library_books</i>
            <p>{{ __('Typography') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('icons') }}">
          <i class="material-icons">bubble_chart</i>
          <p>{{ __('Icons') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('map') }}">
          <i class="material-icons">location_ons</i>
            <p>{{ __('Maps') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('notifications') }}">
          <i class="material-icons">notifications</i>
          <p>{{ __('Notifications') }}</p>
        </a>
      </li>

{{--      <li class="nav-item active-pro{{ $activePage == 'upgrade' ? ' active' : '' }}">--}}
{{--        <a class="nav-link" href="{{ route('upgrade') }}">--}}
{{--          <i class="material-icons">unarchive</i>--}}
{{--          <p>{{ __('Upgrade to PRO') }}</p>--}}
{{--        </a>--}}
{{--      </li>--}}
    </ul>
  </div>
</div>
