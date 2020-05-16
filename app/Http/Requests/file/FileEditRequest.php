<?php

namespace App\Http\Requests\file;

use App\Models\File;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FileEditRequest extends FormRequest
{
    /**
     * Determine if the file is authorized to make this request.
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
