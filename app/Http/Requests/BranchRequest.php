<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'name' => 'sometimes',
            'location' => 'sometimes',
            'google_maps' => 'sometimes',
            'image' => 'required|image',
            'visible' => 'boolean',
            'store_id' => 'sometimes|numeric|exists:stores,id',
            'category_id' => 'sometimes|numeric|exists:categories,id',
        ];
    }
}
