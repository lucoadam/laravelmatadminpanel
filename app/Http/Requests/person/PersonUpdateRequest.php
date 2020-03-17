<?php

namespace App\Http\Requests\person;

use App\Models\Person;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PersonUpdateRequest extends FormRequest
{
    /**
     * Determine if the person is authorized to make this request.
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
