@extends('layouts.app', ['activePage' => 'institute-management', 'titlePage' => __('Institute Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title ">{{ __('Institute') }}</h4>
                <p class="card-category"> {{ __('Here you can manage institute') }}</p>
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
                        <a href="{{ route('institute.create') }}" class="btn btn-sm btn-primary">{{ __('Add Institute') }}</a>
                    </div>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                    <th>
                        {{ __('Id') }}
                    </th>
						<th>
                            {{ __("Name") }}
                          </th>
						<th>
                            {{ __("Abbr") }}
                          </th>
						<th>
                            {{ __("Full_abbr") }}
                          </th>
                      <th>
                        {{ __('Creation date') }}
                      </th>
                      <th class="text-right">
                        {{ __('Actions') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($institute as $model)
                        <tr>
                            <td>
                                {{$model->id}}
                            </td>
						<td>
                            {{ $model->name }}
                          </td>
						<td>
                            {{ $model->abbr }}
                          </td>
						<td>
                            {{ $model->full_abbr }}
                          </td>
                          <td>
                            {{ $model->created_at->format('Y/m/d') }}
                          </td>
                          <td class="td-actions text-right">
                              <form action="{{ route('institute.destroy', $model) }}" method="post">
                                  @csrf
                                  @method('delete')
                              
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('institute.edit', $model) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this institute?") }}') ? this.parentElement.submit() : ''">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                              </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                    {{ $institute->links() }}
                </div>
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