<?php

namespace App\Http\Requests\menus;

use App\Models\Menus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MenusEditRequest extends FormRequest
{
    /**
     * Determine if the menus is authorized to make this request.
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
