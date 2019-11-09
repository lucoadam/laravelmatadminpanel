<?php

namespace App\Http\Requests;

use App\Models\Library;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class LibraryRequest extends FormRequest
{
    /**
     * Determine if the library is authorized to make this request.
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
			'name' => [
                'required'
            ],
			'content' => [
                'required'
            ],
        ];
    }
}
