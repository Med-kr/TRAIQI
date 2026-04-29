<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendSchoolMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'role' => ['nullable', Rule::in(['student', 'parent', 'teacher', 'school_admin'])],
            'action_url' => ['nullable', 'string', 'max:255'],
        ];
    }
}
