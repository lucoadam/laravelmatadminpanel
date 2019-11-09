<?php

namespace App\Http\Requests;

use App\Models\Menu;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the menu is authorized to make this request.
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
			'name' => [
                'required'
            ],
			'icon' => [
                'required'
            ],
			'url_type' => [
                'required'
            ],
			'url' => [
                'required'
            ],
			'open_in_new_tab' => [
                'required'
            ],
        ];
    }
}
