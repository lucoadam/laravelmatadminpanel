<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="/" class="simple-text logo-normal" style="text-align:center">
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
          $parent= \App\Models\Menu::select(['id','name','url','icon'])->orderBy('name')->where('parent_id',0)->get();

     foreach($parent as $p){
          if(isset($activePage)&& $activePage == strtolower($p->name).'-management'){
            $p->isActive = true;
          }else{
              $p->isActive=false;
          }
         $child =\App\Models\Menu::select(['id','name','url','icon'])->orderBy('name')->where('parent_id',$p->id);
         if($child->exists()){
             $children=$child->get();
             foreach ($children as $item) {
                  if(isset($activePage)&& $activePage == strtolower($item->name).'-management'){
                    $item->isActive = true;
                     $p->isActive = true;
                  }else{
                      $item->isActive=false;
                  }
             }
             $p['children']=$children;
         }

     }
    ;
      @endphp
      @foreach($parent as $par)
            <li class="nav-item{{ $par->isActive ? ' active' : '' }}" >
                <a class="nav-link {{ $par->url=='#' ? ($par->isActive?'':'collapsed') : '' }}" {!! $par->url=='#' ? 'aria-expanded="'.($par->isActive?'true':'false').'"' : '' !!} href="{{$par->url!='#'?route($par->url):$par->url.strtolower($par->name).'Management'}}" {!! $par->url=='#'?'data-toggle="collapse"':''!!}>
                    <i class="material-icons">{{$par->icon??'library_books'}}</i>
                    <p>{{ $par->name }}{!! $par->url=='#'?'<b class="caret"></b>':''!!}</p>
                </a>
                @if($par->url=='#'&& isset($par->children))
                    <div class="collapse {{ $par->isActive ? 'show' : '' }}" id="{{strtolower($par->name).'Management'}}">
                        <ul class="nav">
                            @foreach($par->children as $child)
                            <li class="nav-item{{ $child->isActive ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route($child->url) }}">
                                    <i class="material-icons">{{$child->icon??'library_books'}}</i>
                                    <span class="sidebar-normal">{{ $child->name.' Management' }} </span>
                                </a>
                            </li>
                                @endforeach
                        </ul>
                    </div>
                @endif
            </li>
      @endforeach
{{--      <li class="nav-item{{ $activePage == 'table' ? ' active' : '' }}">--}}
{{--        <a class="nav-link" href="{{ route('table') }}">--}}
{{--          <i class="material-icons">content_paste</i>--}}
{{--            <p>{{ __('Table List') }}</p>--}}
{{--        </a>--}}
{{--      </li>--}}
{{--      <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">--}}
{{--        <a class="nav-link" href="{{ route('typography') }}">--}}
{{--          <i class="material-icons">library_books</i>--}}
{{--            <p>{{ __('Typography') }}</p>--}}
{{--        </a>--}}
{{--      </li>--}}
{{--      <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">--}}
{{--        <a class="nav-link" href="{{ route('icons') }}">--}}
{{--          <i class="material-icons">bubble_chart</i>--}}
{{--          <p>{{ __('Icons') }}</p>--}}
{{--        </a>--}}
{{--      </li>--}}
{{--      <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">--}}
{{--        <a class="nav-link" href="{{ route('map') }}">--}}
{{--          <i class="material-icons">location_ons</i>--}}
{{--            <p>{{ __('Maps') }}</p>--}}
{{--        </a>--}}
{{--      </li>--}}
{{--      <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">--}}
{{--        <a class="nav-link" href="{{ route('notifications') }}">--}}
{{--          <i class="material-icons">notifications</i>--}}
{{--          <p>{{ __('Notifications') }}</p>--}}
{{--        </a>--}}
{{--      </li>--}}

{{--      <li class="nav-item active-pro{{ $activePage == 'upgrade' ? ' active' : '' }}">--}}
{{--        <a class="nav-link" href="{{ route('upgrade') }}">--}}
{{--          <i class="material-icons">unarchive</i>--}}
{{--          <p>{{ __('Upgrade to PRO') }}</p>--}}
{{--        </a>--}}
{{--      </li>--}}
    </ul>
  </div>
</div>

{{--<script>--}}
{{--    function save(data,filename){--}}
{{--        if(!data) {--}}
{{--            console.error('Console.save: No data')--}}
{{--            return;--}}
{{--        }--}}

{{--        if(!filename) filename = 'console.json'--}}

{{--        if(typeof data === "object"){--}}
{{--            data = JSON.stringify(data, undefined, 4)--}}
{{--        }--}}

{{--        var blob = new Blob([data], {type: 'text/json'}),--}}
{{--            e    = document.createEvent('MouseEvents'),--}}
{{--            a    = document.createElement('a')--}}

{{--        a.download = filename--}}
{{--        a.href = window.URL.createObjectURL(blob)--}}
{{--        a.dataset.downloadurl =  ['text/json', a.download, a.href].join(':')--}}
{{--        e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null)--}}
{{--        a.dispatchEvent(e)--}}
{{--    }--}}
{{--</script>--}}
