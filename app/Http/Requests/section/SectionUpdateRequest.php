<?php

namespace App\Http\Requests\section;

use App\Models\Section;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SectionUpdateRequest extends FormRequest
{
    /**
     * Determine if the section is authorized to make this request.
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
