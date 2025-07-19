<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserTimerPresetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'work_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'break_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'long_break_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'long_break_interval' => ['required', 'integer', 'min:1', 'max:10'],
            'auto_play' => ['boolean'],
            // 'notifications_enabled' => ['boolean'],
        ];
    }
}
