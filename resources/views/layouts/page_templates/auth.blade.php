<div class="wrapper ">
  @include('layouts.navbars.sidebar')
  <div class="main-panel ps-container ps-theme-default">
    @include('layouts.navbars.navs.auth')
    @yield('content')
    @include('layouts.footers.auth')
  </div>
</div>
