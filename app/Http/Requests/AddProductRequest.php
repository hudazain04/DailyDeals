<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
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
            'name'=>'required|string',
            'category_id' => 'required|integer',
            'store_id' => 'required|integer',
            'price' => 'required|integer',
            'colors' => 'required|array',
            'colors.*.color' => 'required|int',
            'colors.*.image' => 'required|image',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|integer',
            'sizes.*.unit' => 'required|string',


        ];


    }
}
