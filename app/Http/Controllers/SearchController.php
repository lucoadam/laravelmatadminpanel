<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    //
    public function search(Request $request,$table)
    {
        if($request->token == csrf_token()) {

            $modelName = explode('s', $table);
            array_pop($modelName);
            $modelName = implode('s', $modelName);
            $columns = $request->fields;
            $output = "";
            $searchFields= $request->searchFields;
            if(is_array($searchFields)){
                $count=count($searchFields);
                if($count===2) {
                    $products = DB::table($table)->where($searchFields[0], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[1], 'LIKE', '%' . $request->search . "%")
                        ->paginate(5);
                }elseif($count===3){
                    $products = DB::table($table)->where($searchFields[0], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[1], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[2], 'LIKE', '%' . $request->search . "%")
                        ->paginate(5);
                }elseif($count===4){
                    $products = DB::table($table)->where($searchFields[0], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[1], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[2], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[3], 'LIKE', '%' . $request->search . "%")
                        ->paginate(5);
                }elseif($count===5){
                    $products = DB::table($table)->where($searchFields[0], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[1], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[2], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[3], 'LIKE', '%' . $request->search . "%")
                        ->orWhere($searchFields[4], 'LIKE', '%' . $request->search . "%")
                        ->paginate(5);
                }else{
                    $products = DB::table($table)->where($searchFields[0], 'LIKE', '%' . $request->search . "%")
                        ->paginate(5);
                }
            }else{
                $products = DB::table($table)->where($searchFields, 'LIKE', '%' . $request->search . "%")
                    ->paginate(5);
            }

            if (!is_null($products)) {
                foreach ($products as $key => $product) {
                    $each = '';
                    foreach ($columns as $column) {
                        if ($column != 'actions') {
                            $each .= '<td>' . $product->{$column} . '</td>';
                        } else {
                            $each .= '<td class="td-actions text-right">
                              <form action="' . url('/') . '/' . $modelName . '/' . $product->id . '" method="post">
                                  ' . csrf_field() . '
                                   <input type="hidden" name="_method" value="delete"/>
                                  <a rel="tooltip" class="btn btn-success btn-link" href="' . url('/') . '/' . $modelName . '/' . $product->id . '/edit" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                    <div class="ripple-container"></div>
                                  </a>
                                  <button type="button" class="btn btn-danger btn-link" data-original-title="" title="" onclick="confirm(\'Are you sure you want to delete this ' . $modelName . '?\')? this.parentElement.submit() : \'\'">
                                      <i class="material-icons">close</i>
                                      <div class="ripple-container"></div>
                                  </button>
                              </form>
                          </td>';
                        }
                    }
                    $output .= '<tr>' . $each . '</tr>';
                }
            }else{
                $output = '<tr>'.'<td'.'colspan="'.count($columns).'">'.'No content found'.'</td>'.'</tr>';
            }
            $response = new \stdClass();
            $response->paginate = is_array($searchFields);
            $response->content = $output;
            return json_encode($response);
        }
    }
}
