<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'user_id' => ['nullable', 'integer'],
            'type' => ['nullable', 'string', Rule::in(['system', 'grade', 'review_request', 'school_message', 'meeting_request', 'password_reset'])],
            'action_url' => ['nullable', 'string', 'max:255'],
        ];
    }
}
