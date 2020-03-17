<?php

namespace App\Http\Requests\institute;

use App\Models\Institute;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InstituteCreateRequest extends FormRequest
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
        ];
    }
}
