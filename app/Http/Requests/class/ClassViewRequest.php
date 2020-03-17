<?php

namespace App\Http\Requests\class;

use App\Models\Class;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClassViewRequest extends FormRequest
{
    /**
     * Determine if the class is authorized to make this request.
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
