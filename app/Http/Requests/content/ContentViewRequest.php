<?php

namespace App\Http\Requests\content;

use App\Models\Content;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ContentViewRequest extends FormRequest
{
    /**
     * Determine if the content is authorized to make this request.
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
