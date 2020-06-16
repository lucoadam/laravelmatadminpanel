<div class="sidebar" data-color="rose" data-background-color="black">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="/" class="simple-text logo-normal" style="text-align:center">
      {{ env('APP_NAME','Admin Panel') }}
    </a>
  </div>
  <div class="sidebar-wrapper ps-container ps-theme-default ps-active-y">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
        @if(auth()->user()->allow('view-user'))
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
      @else
            <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <span class="sidebar-mini"> UP </span>
                    <span class="sidebar-normal">{{ __('User profile') }} </span>
                </a>
            </li>
      @endif

      @if(auth()->user()->allow('view-modules'))
      <li class="nav-item {{ ($activePage == 'department-management') ? ' active' : '' }}">
        <a class="nav-link {{ ($activePage == 'department-management') ? '' : 'collapsed' }}" data-toggle="collapse" href="#settingManagement" aria-expanded="{{ ($activePage == 'department-management') ? 'true' : 'false' }}">
          {{--          <i><img style="width:25px" src="{{ asset('material') }}/img/laravel.svg"></i>--}}
          <p>{{ __('Setting Management') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'profile' || $activePage == 'module-management') ? 'show' : '' }}" id="settingManagement">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'module-management' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('settings.module.index') }}">
                <span class="sidebar-mini"> UP </span>
                <span class="sidebar-normal">{{ __('Module Management') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
        @endif
      @php
          $parent= \App\Models\Menu::select(['id','name','url','icon'])->orderBy('name')->where('parent_id',0)->get();

     foreach($parent as $p){
          if(isset($activePage)&& $activePage == implode('',explode(' ',strtolower($p->name))).'-management'){
            $p->isActive = true;
          }else{
              $p->isActive=false;
          }
         $child =\App\Models\Menu::select(['id','name','url','icon'])->orderBy('name')->where('parent_id',$p->id);
         if($child->exists()){
             $children=$child->get();
             foreach ($children as $item) {
                  if(isset($activePage)&& $activePage == implode('',explode(' ',strtolower($item->name))).'-management'){
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
          @if(auth()->user()->allow('view-'.strtolower($par->name)))
            <li class="nav-item{{ $par->isActive ? ' active' : '' }}" >
                <a class="nav-link {{ $par->url=='#' ? ($par->isActive?'':'collapsed') : '' }}" {!! $par->url=='#' ? 'aria-expanded="'.($par->isActive?'true':'false').'"' : '' !!} href="{{$par->url!='#'?route($par->url):$par->url.strtolower($par->name).'Management'}}" {!! $par->url=='#'?'data-toggle="collapse"':''!!}>
                    <i class="material-icons">{{$par->icon??'library_books'}}</i>
                    <p>{{ $par->name }}{!! $par->url=='#'?'<b class="caret"></b>':''!!}</p>
                </a>
                @if($par->url=='#'&& isset($par->children))
                    <div class="collapse {{ $par->isActive ? 'show' : '' }}" id="{{strtolower($par->name).'Management'}}">
                        <ul class="nav">
                            @foreach($par->children as $child)
                                @if(auth()->user()->allow('view-'.strtolower($child->name)))
                            <li class="nav-item{{ $child->isActive ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route($child->url) }}">
                                    <i class="material-icons">{{$child->icon??'library_books'}}</i>
                                    <span class="sidebar-normal">{{ $child->name.' Management' }} </span>
                                </a>
                            </li>
                                @endif
                                @endforeach
                        </ul>
                    </div>
                @endif
            </li>
            @endif
      @endforeach
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
