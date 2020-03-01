<?php

namespace App\Http\Requests\memo;

use App\Models\Memo;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MemoDeleteRequest extends FormRequest
{
    /**
     * Determine if the memo is authorized to make this request.
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
