<?php

namespace App\Http\Requests\Timer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserTimerStatsRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'total_work_time' => ['required', 'integer', 'between:1,999'],
            'total_break_time' => ['required', 'integer', 'between:1,999'],
            'total_rounds_completed' => ['required', 'integer', 'between:1,10'],
        ];
    }
}
