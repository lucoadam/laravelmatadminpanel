<footer class="footer">
  <div class="container-fluid">
    <nav class="float-left">
      <ul>
        <li>
          <a rel="noopener" href="https://stsolutions.com.np">
              {{ __('Sustinable Technological Solution') }}
          </a>
        </li>
        <li>
          <a rel="noopener" href="https://creative-tim.com/presentation">
              {{ __('About Us') }}
          </a>
        </li>
        <li>
          <a rel="noopener" href="http://blog.creative-tim.com">
              {{ __('Blog') }}
          </a>
        </li>
        <li>
          <a rel="noopener" href="https://stsolutions.com.np/license">
              {{ __('Licenses') }}
          </a>
        </li>
        <li>
          <a rel="noopener">
            <button class="btn btn-primary btn-fill" onclick='swal({ title:"Good job!", text: "You clicked the button!", type: "success", buttonsStyling: false, confirmButtonClass: "btn btn-success"})'>Try me!</button>
          </a>
        </li>
      </ul>
    </nav>
    <div class="copyright float-right">
      &copy;
      {{now()->format('Y')}}, made with <i class="material-icons">favorite</i> by
      <a rel="noopener" href="https://stsolutions.com.np" target="_blank">Sustinable Technological Solution</a> for a better web.
    </div>
  </div>
</footer>