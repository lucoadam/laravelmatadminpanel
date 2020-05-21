<?php

namespace App\Http\Requests\client;

use App\Models\Client;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientViewRequest extends FormRequest
{
    /**
     * Determine if the client is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->check()){
            return auth()->user()->allow('view-client');
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
