<?php

namespace App\Http\Requests;

use App\Models\Staff;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the staff is authorized to make this request.
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
			'country' => [
                'required'
            ],
			'city' => [
                'required'
            ],
			'salary' => [
                'required'
            ],
        ];
    }
}
