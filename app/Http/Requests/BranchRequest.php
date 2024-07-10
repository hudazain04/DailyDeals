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
            'name' => 'required',
            'location' => 'required',
            'google_maps' => 'required',
            'image' => 'file',
            'visible' => 'boolean',
            'store_id' => 'required|numeric|exists:stores,id',
            'category_id' => 'required|numeric|exists:categories,id',         
        ];
    }
}
