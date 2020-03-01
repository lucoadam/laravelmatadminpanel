<?php

namespace App\Http\Requests\hospital;

use App\Models\Hospital;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class HospitalUpdateRequest extends FormRequest
{
    /**
     * Determine if the hospital is authorized to make this request.
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
        ];
    }
}
