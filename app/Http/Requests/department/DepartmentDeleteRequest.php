<?php

namespace App\Http\Requests\department;

use App\Models\Department;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentDeleteRequest extends FormRequest
{
    /**
     * Determine if the department is authorized to make this request.
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
