@extends('layouts.app', ['activePage' => 'menus-management', 'titlePage' => __('Menus Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title ">{{ __('Menus') }}</h4>
                <p class="card-category"> {{ __('Here you can manage menus') }}</p>
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
                        <a href="{{ route('menus.create') }}" class="btn btn-sm btn-primary">{{ __('Add Menus') }}</a>
                        <button clas="btn btn-sm btn-primary" onclick="save({{json_encode($menus)}})">Save</button>
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
                            {{ __("Icon") }}
                          </th>
						<th>
                            {{ __("Url type") }}
                          </th>
						<th>
                            {{ __("Url") }}
                          </th>
						<th>
                            {{ __("Parent id") }}
                          </th>
						<th>
                            {{ __("Backend") }}
                          </th>
						<th>
                            {{ __("Open in new tab") }}
                          </th>
                      <th>
                        {{ __('Creation date') }}
                      </th>
                      <th class="text-right">
                        {{ __('Actions') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($menus as $model)
                        <tr>
                            <td>
                                {{$model->id}}
                            </td>
						<td>
                            {{ $model->name }}
                          </td>
						<td>
                            {{ $model->icon }}
                          </td>
						<td>
                            {{ $model->url_type }}
                          </td>
						<td>
                            {{ $model->url }}
                          </td>
						<td>
                            {{ $model->parent_id }}
                          </td>
						<td>
                            {{ $model->backend }}
                          </td>
						<td>
                            {{ $model->open_in_new_tab }}
                          </td>
                          <td>
                            {{ $model->created_at->format('Y/m/d') }}
                          </td>
                          <td class="td-actions text-right">
                              <form action="{{ route('menus.destroy', $model) }}" method="post">
                                  @csrf
                                  @method('delete')

                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('menus.edit', $model) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this menus?") }}') ? this.parentElement.submit() : ''">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                              </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                    {{ $menus->links() }}
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
            $('#searchTable').on('keyup',function() {
                $value = $(this).val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    type: 'get',
                    url: '{{URL::to('search/menuss')}}',
                    data: {
                        'search': $value,
                        'searchFields': ["id","name","icon","url_type","url","parent_id","backend","open_in_new_tab","created_at"],
                        'token': '{{csrf_token()}}',
                        'fields': ["id","name","icon","url_type","url","parent_id","backend","open_in_new_tab","created_at","actions"]
                    },
                    success: function (data) {
                        var d = JSON.parse(data)
                        console.log(d.paginate)
                        $('tbody').html(d.content);
                    }
                });
            });
        })

    </script>
    <script>
        function convertToCSV(objArray) {
            var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
            var str = '';
            var keys = Object.keys(array[0]);var line = '';for(let i=0;i<keys.length;i++){if (line != '') line += ','

                line += keys[i].toUpperCase()}str+=line+'\r\n';
            for (var i = 0; i < array.length; i++) {
                line='';
                for (var index in array[i]) {
                    if (line != '') line += ','

                    line += array[i][index];
                }

                str += line + '\r\n';
            }

            return str;
        }
        function save(data){
            if(!data) {
                console.error('Console.save: No data')
                return;
            }

            var filename = 'console.csv'

            if(typeof data === "object"){
                data = JSON.stringify(data['data'], undefined, 4);
                data = convertToCSV(data);
            }

            var blob = new Blob([data], {type: 'text/csv'}),
                e    = document.createEvent('MouseEvents'),
                a    = document.createElement('a')

            a.download = filename
            a.href = window.URL.createObjectURL(blob)
            a.dataset.downloadurl =  ['text/csv', a.download, a.href].join(':')
            e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null)
            a.dispatchEvent(e)
        }
    </script>
@endsection
