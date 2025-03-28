<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => ['nullable', Rule::in(['Male', 'Female'])],
            'bio' => 'nullable|string|max:500',
            'phone' => [
                'nullable',
                'string',
                'max:15',
                Rule::unique('users')->ignore($userId),
            ],
            'role' => ['nullable', Rule::in(['user', 'admin'])],
        ];
    }
}
