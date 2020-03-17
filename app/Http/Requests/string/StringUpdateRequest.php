<?php

namespace App\Http\Requests\string;

use App\Models\String;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StringUpdateRequest extends FormRequest
{
    /**
     * Determine if the string is authorized to make this request.
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
