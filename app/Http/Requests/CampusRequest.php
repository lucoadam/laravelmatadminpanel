<?php

namespace App\Http\Requests;

use App\Models\Campus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CampusRequest extends FormRequest
{
    /**
     * Determine if the campus is authorized to make this request.
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
			'no_of_students' => [
                'required'
            ],
        ];
    }
}
