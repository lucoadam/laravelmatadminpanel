<?php

namespace App\Http\Requests;

use App\Models\Onion;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OnionRequest extends FormRequest
{
    /**
     * Determine if the onion is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'onion' => [
                'required'
            ],
        ];
    }
}
