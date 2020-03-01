<?php

namespace App\Http\Requests\memory;

use App\Models\Memory;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MemoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the memory is authorized to make this request.
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