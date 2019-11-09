<?php

namespace App\Http\Requests;

use App\Models\Institute;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InstituteRequest extends FormRequest
{
    /**
     * Determine if the institute is authorized to make this request.
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
			'abbr' => [
                'required'
            ],
			'full_abbr' => [
                'required'
            ],
        ];
    }
}
