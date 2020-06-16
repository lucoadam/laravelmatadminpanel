@extends('layouts.app', ['activePage' => 'module-management', 'titlePage' => __('Module Management')])

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
                <h4 class="card-title ">{{ __('Modules') }}</h4>
                <p class="card-category"> {{ __('Here you can manage modules') }}</p>
              </div>
              <div class="card-body col-sm-12">
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
                <div class="col-12 text-right" >
                        <a href="{{ route('settings.module.create') }}" class="btn btn-sm btn-primary">{{ __('Add Module') }}</a>
                    </div>

                    @if(isset($modules))
                <div>
                  <table id="dataTable" class="table table-striped table-no-bordered table-hover dataTable no-footer dtr-inline"  style="width:100%">
                    <thead class=" text-primary">
                    <th >
                        {{ __('S.N.') }}
                    </th>
                      <th>
                          {{ __('Name') }}
                      </th>
                      <th>
                        {{ __('Creation date') }}
                      </th>
                      <th>
                        {{ __('Actions') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($modules as $module)
                        <tr>
                            <td tabindex="0">
                                {{$module->index}}
                            </td>
                          <td>
                            {{ $module->name }}
                          </td>
                          <td>
                            {{ $module->created_at->format('Y-m-d') }}
                          </td>
                          <td class="td-actions">
                              <form id="{{__('formModule'.$module->id)}}" action="{{ route('settings.module.destroy', $module) }}" method="post">
                                  @csrf
                                  @method('delete')

                                  <a rel="tooltip" class="btn btn-disabled btn-success btn-link" href="{{-- route('settings.module.edit', $module) --}}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-link" data-toggle="modal" data-target="#moduleDelete{{$module->id}}" data-original-title="" title="">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>

                                  <!-- Modal -->
                                  <div class="modal fade" id="moduleDelete{{$module->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">Delete Module</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="button" onClick="document.getElementById('{{__('formModule'.$module->id)}}').submit()" class="btn btn-primary">Yes</button>
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>

                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                        @endif
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('after-script')
<script type="text/javascript">
    $(document).ready(function() {
      $('#dataTable').fadeIn(1100);
      $('#dataTable').DataTable({

        "pagingType": "full_numbers",
        "lengthMenu": [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ],
        responsive: true,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search Modules",
        },
        "columnDefs": [
          { "orderable": false, "targets":3 },
        ],
      });
    });
    // $(document).ready(function () {
    //     $('#dataTable').DataTable( {
    //         autoWidth: false,
    //         columnDefs: [
    //             {
    //                 targets: ['_all'],
    //                 className: 'mdc-data-table__cell'
    //             }
    //         ],
    //     } );
    // })
</script>
@endsection
