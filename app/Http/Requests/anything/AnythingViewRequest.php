<?php

namespace App\Http\Requests\anything;

use App\Models\Anything;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AnythingViewRequest extends FormRequest
{
    /**
     * Determine if the anything is authorized to make this request.
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
