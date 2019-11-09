@extends('layouts.app', ['activePage' => 'department-management', 'titlePage' => __('Department Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title ">{{ __('Departments') }}</h4>
                <p class="card-category"> {{ __('Here you can manage department') }}</p>
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

                  <div class=" col-7 right no-border">
                    <input id="searchTable" type="text" value="" class="form-control" placeholder="Search...">
                  </div>
                    <div class="col-5 text-right">
                        <a href="{{ route('settings.department.create') }}" class="btn btn-sm btn-primary">{{ __('Add Department') }}</a>
                    </div>
                </div>
                    @if(isset($departments))
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                    <th>
                        {{ __('Id') }}
                    </th>
                      <th>
                          {{ __('Name') }}
                      </th>
                      <th>
                        {{ __('Creation date') }}
                      </th>
                      <th class="text-right">
                        {{ __('Actions') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($departments as $department)
                        <tr>
                            <td>
                                {{$department->id}}
                            </td>
                          <td class="search-content">
                            {{ $department->name }}
                          </td>
                          <td>
                            {{ $department->created_at->format('Y-m-d') }}
                          </td>
                          <td class="td-actions text-right">
                              <form id="{{__('formDepartment'.$department->id)}}" action="{{ route('settings.department.destroy', $department) }}" method="post">
                                  @csrf
                                  @method('delete')
                              
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('settings.department.edit', $department) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-link" data-toggle="modal" data-target="#departmentDelete{{$department->id}}" data-original-title="" title="">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>

                                  <!-- Modal -->
                                  <div class="modal fade" id="departmentDelete{{$department->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">Delete Department</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="button" onClick="document.getElementById('{{__('formDepartment'.$department->id)}}').submit()" class="btn btn-primary">Yes</button>
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
                    {{ $departments->links() }}
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
        $(document).ready(function () {
            $('#searchTable').on('keyup',function(e){
                d=$('.search-content');
                for(let i=0;i<d.length;i++){
                    if(d[i].innerText.match(e.target.value)){
                        d[i].parentElement.removeAttribute('style');
                        continue;
                    }
                    d[i].parentElement.style.display='none';
                }
            })
        })
    </script>
@endsection