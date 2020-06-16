@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('User Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-rose card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons">group</i>
                    </div>
                    <h4 class="card-title ">{{ __('Users') }}</h4>
                <p class="card-category"> {{ __('Here you can manage users') }}</p>
                </div>
                <div class="card-body">
                    <div class="row">
      <div class="col-12 text-right">
        <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">Add user</a>
      </div>
    </div>
        <table id="datatables" class="table table-striped table-no-bordered table-hover dataTable no-footer dtr-inline" style="width:100%" role="grid" aria-describedby="datatables_info">
            <thead class="text-primary">

              <tr role="row">
                <th class="sorting_asc" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-sort="ascending" aria-label="
                {{ __('S.N.') }}
            : activate to sort column descending">
                    {{ __('S.N.') }}
                </th>
                  <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="
                  {{ __('Name') }}
              : activate to sort column ascending">
                      {{ __('Name') }}
                  </th>
                  <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="
                  {{ __('Email') }}
              : activate to sort column ascending">
                    {{ __('Email') }}
                  </th>
                  <th class="sorting" tabindex="0" aria-controls="datatables" rowspan="1" colspan="1" aria-label="
                  {{ __('Creation date') }}
                : activate to sort column ascending">
                    {{ __('Creation date') }}
                  </th>
                  <th class="text-right sorting_disabled" rowspan="1" colspan="1"  aria-label="
                  {{ __('Actions') }}
                ">
                    {{ __('Actions') }}
                  </th>

            </tr>
            </thead>
            <tbody>

                @foreach($users as $key=>$user)

                                  <tr role="row" class="{{$key%2==0?'odd':'even'}}">
                  <td tabindex="0" class="sorting_1">
                    {{$user->index}}
                  </td>
                  <td>
                    {{ $user->name }}
                  </td>
                  <td>
                    {{ $user->email }}
                  </td>
                  <td>
                    {{ $user->created_at->format('Y-m-d') }}
                  </td>
                  <td class="td-actions text-right">
                    @if ($user->id != auth()->id())
                      <form action="{{ route('user.destroy', $user) }}" method="post">
                          @csrf
                          @method('delete')

                          <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('user.edit', $user) }}" data-original-title="" title="">
                            <i class="material-icons">edit</i>
                            <div class="ripple-container"></div>
                          </a>
                          <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this user?") }}') ? this.parentElement.submit() : ''">
                              <i class="material-icons">close</i>
                              <div class="ripple-container"></div>
                          </button>
                      </form>
                    @else
                      <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('profile.edit') }}" data-original-title="" title="">
                        <i class="material-icons">edit</i>
                        <div class="ripple-container"></div>
                      </a>
                    @endif
                        </td>
                                          </tr>
                                          @endforeach

                    </tbody>
          </table>


</div>
  </div>
</div>

            </div>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('after-script')
<script src="https://material-dashboard-pro-laravel.creative-tim.com/material/js/plugins/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
      $('#datatables').fadeIn(1100);
      $('#datatables').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search users",
        },
        "columnDefs": [
          { "orderable": false, "targets":4},
        ],
      });
    });
  </script>
@endsection
