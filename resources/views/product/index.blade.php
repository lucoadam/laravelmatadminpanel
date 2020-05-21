@extends('layouts.app', ['activePage' => 'product-management', 'titlePage' => __('Product Management')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <div class="card">
              <div class="card-header card-header-primary">
                <h4 class="card-title ">{{ __('Product') }}</h4>
                <p class="card-category"> {{ __('Here you can manage product') }}</p>
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
                 @if(auth()->user()->allow('create-'.strtolower('Product')))
                <div class="row">
                    <div class="col-5">
                        <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary">{{ __('Add Product') }}</a>
                    </div>
                </div>
                @endif
                <div class="table-responsive">
                  <table id="dataTable" class="table">
                    <thead class=" text-primary">
                    <th>
                        {{ __('Id') }}
                    </th>
						<th>
                            {{ __("Name") }}
                          </th>
                      <th>
                        {{ __('Creation date') }}
                      </th>
                      <th class="text-right">
                        {{ __('Actions') }}
                      </th>
                    </thead>
                    <tbody>
                      @foreach($product as $model)
                        <tr>
                            <td>
                                {{$model->id}}
                            </td>
						<td>
                                {{$model->name}}
                          </td>
                          <td>
                            {{ $model->created_at->format('Y/m/d') }}
                          </td>
                          <td class="td-actions text-right">
                                 @if(auth()->user()->allow('delete-'.strtolower('Product')))
                              <form action="{{ route('product.destroy', $model) }}" method="post">
                                  @csrf
                                  @method('delete')
                                  @endif
                                  @if(auth()->user()->allow('edit-'.strtolower('Product')))
                                  <a rel="tooltip" class="btn btn-success btn-link" href="{{ route('product.edit', $model) }}" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  @endif
                                   @if(auth()->user()->allow('delete-'.strtolower('Product')))
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm('{{ __("Are you sure you want to delete this product?") }}') ? this.parentElement.submit() : ''">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                                  @endif
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
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dataTable').DataTable( {
                autoWidth: false,
                columnDefs: [
                    {
                        targets: ['_all'],
                        className: 'mdc-data-table__cell'
                    }
                ],
            } );
        })

    </script>
@endsection