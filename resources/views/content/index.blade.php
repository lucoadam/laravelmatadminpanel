@extends('layouts.app', ['activePage' => 'content-management', 'titlePage' => __('Content Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title ">{{ __('Content') }}</h4>
                <p class="card-category"> {{ __('Here you can manage content') }}</p>
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
                        <a href="{{ route('content.create') }}" class="btn btn-sm btn-primary">{{ __('Add Content') }}</a>
                    </div>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                    <th>
                        {{ __('Id') }}
                    </th>
						<th>
                            {{ __("Title") }}
                          </th>
						<th>
                            {{ __("Image id") }}
                          </th>
						<th>
                            {{ __("File id") }}
                          </th>
						<th>
                            {{ __("Description") }}
                          </th>
						<th>
                            {{ __("Type") }}
                          </th>
						<th>
                            {{ __("Posted by") }}
                          </th>
                      <th>
                        {{ __('Creation date') }}
                      </th>
                      <th class="text-right">
                        {{ __('Actions') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($content as $model)
                        <tr>
                            <td>
                                {{$model->id}}
                            </td>
						<td>
                                {{$model->title}}
                          </td>
						<td>
                                {{$model->image_id}}
                          </td>
						<td>
                                {{$model->file_id}}
                          </td>
						<td>
                                {{$model->description}}
                          </td>
						<td>
                                {{$model->type}}
                          </td>
						<td>
                                {{$model->posted_by}}
                          </td>
                          <td>
                            {{ $model->created_at->format('Y/m/d') }}
                          </td>
                          <td class="td-actions text-right">
                              <form action="{{ route('content.destroy', $model) }}" method="post">
                                  @csrf
                                  @method('delete')

                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('content.edit', $model) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this content?") }}') ? this.parentElement.submit() : ''">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                              </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                    {{ $content->links() }}
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
                    url: '{{URL::to('search/contents')}}',
                    data: {
                        'search': $value,
                        'searchFields': ["id","title","image_id","file_id","description","type","posted_by","created_at"],
                        'token': '{{csrf_token()}}',
                        'fields': ["id","title","image_id","file_id","description","type","posted_by","created_at","actions"]
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
@endsection