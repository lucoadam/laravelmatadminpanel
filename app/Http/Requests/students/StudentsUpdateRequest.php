<?php

namespace App\Http\Requests\students;

use App\Models\Students;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StudentsUpdateRequest extends FormRequest
{
    /**
     * Determine if the students is authorized to make this request.
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
