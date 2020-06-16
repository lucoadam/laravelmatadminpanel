<div class="wrapper ">
  @include('layouts.navbars.sidebar')
  <div class="main-panel ps-container ps-theme-default" data-ps-id="f0b4fdf0-4822-18c6-cdc7-02c0f3eab9a3">
    @include('layouts.navbars.navs.auth')
    @yield('content')
    @include('layouts.footers.auth')
  </div>
</div>
