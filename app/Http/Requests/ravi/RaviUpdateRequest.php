<?php

namespace App\Http\Requests\ravi;

use App\Models\Ravi;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RaviUpdateRequest extends FormRequest
{
    /**
     * Determine if the ravi is authorized to make this request.
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
