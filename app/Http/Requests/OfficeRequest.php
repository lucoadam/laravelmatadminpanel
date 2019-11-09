<?php

namespace App\Http\Requests;

use App\Models\Office;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OfficeRequest extends FormRequest
{
    /**
     * Determine if the office is authorized to make this request.
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
			'staff_no' => [
                'required'
            ],
        ];
    }
}
