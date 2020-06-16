@extends('layouts.app', ['activePage' => 'permission-management', 'titlePage' => __('Permission Management')])

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
                <h4 class="card-title ">{{ __('Permission') }}</h4>
                <p class="card-category"> {{ __('Here you can manage permission') }}</p>
              </div>
              <div class="card-body">
                @if (session('status'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="material-icons">close</i>
                        </button>
                        <span>{{ session('status') }}</span>
                      </div>
                    </div>
                  </div>
                @endif
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('permission.create') }}" class="btn btn-sm btn-primary">{{ __('Add Permission') }}</a>
                    </div>
                </div>
                <div class="table-responsive">
                  <table id="dataTable" class="table">
                    <thead class=" text-primary">
                    <th>
                        {{ __('S.N.') }}
                    </th>
						<th>
                            {{ __("Name") }}
                          </th>
						<th>
                            {{ __("Display name") }}
                          </th>
                      <th>
                        {{ __('Creation date') }}
                      </th>
                      <th class="text-right">
                        {{ __('Actions') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($permission as $model)
                        <tr>
                            <td>
                                {{$model->index}}
                            </td>
						<td>
                                {{$model->name}}
                          </td>
						<td>
                                {{$model->display_name}}
                          </td>
                          <td>
                            {{ $model->created_at->format('Y/m/d') }}
                          </td>
                          <td class="td-actions text-right">
                              <form action="{{ route('permission.destroy', $model) }}" method="post">
                                  @csrf
                                  @method('delete')

                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('permission.edit', $model) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this permission?") }}') ? this.parentElement.submit() : ''">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                              </form>
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
@endsection
@section('after-script')
<script src="https://material-dashboard-pro-laravel.creative-tim.com/material/js/plugins/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
      $('#dataTable').fadeIn(1100);
      $('#dataTable').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        responsive: true,
        autoWidth: false,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search",
        },
        "columnDefs": [
          { "orderable": false, "targets":  4 },
        ],
      });
    });
  </script>
@endsection
