<?php

namespace App\Http\Requests\school;

use App\Models\School;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SchoolUpdateRequest extends FormRequest
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
        ];
    }
}
