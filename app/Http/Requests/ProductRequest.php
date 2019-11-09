<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the product is authorized to make this request.
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
			'title' => [
                'required'
            ],
			'description' => [
                'required'
            ],
			'price' => [
                'required'
            ],
        ];
    }
}
