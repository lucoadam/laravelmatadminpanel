<?php

namespace App\Http\Requests\group;

use App\Models\Group;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GroupStoreRequest extends FormRequest
{
    /**
     * Determine if the group is authorized to make this request.
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
