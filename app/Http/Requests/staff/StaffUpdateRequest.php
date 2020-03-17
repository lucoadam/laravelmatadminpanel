<?php

namespace App\Http\Requests\staff;

use App\Models\Staff;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
{
    /**
     * Determine if the staff is authorized to make this request.
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
