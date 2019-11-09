<?php

namespace App\Http\Requests;

use App\Models\School;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SchoolRequest extends FormRequest
{
    /**
     * Determine if the school is authorized to make this request.
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
			'location' => [
                'required'
            ],
			'description' => [
                'required'
            ],
        ];
    }
}
