<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:15',
            'role'=>'required|string|in:"Customer","Merchant"',
            'image' => 'nullable|image',
        ];
    }
    public function filters()
    {
        return [
            'first_name' => 'trim|strip_tags|escape',
            'last_name' => 'trim|strip_tags|escape',
            'email' => 'trim|strip_tags|escape|lowercase',
            'password' => 'trim|strip_tags',
            'phone_number' => 'trim|lowercase',
            'role'=>'trim|lowercase',

        ];
    }
}
