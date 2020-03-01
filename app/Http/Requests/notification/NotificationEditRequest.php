<?php

namespace App\Http\Requests\notification;

use App\Models\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class NotificationEditRequest extends FormRequest
{
    /**
     * Determine if the notification is authorized to make this request.
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
