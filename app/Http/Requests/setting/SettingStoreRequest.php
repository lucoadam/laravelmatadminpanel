<?php

namespace App\Http\Requests\setting;

use App\Models\Setting;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SettingStoreRequest extends FormRequest
{
    /**
     * Determine if the setting is authorized to make this request.
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
