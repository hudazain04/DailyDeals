<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSizesRequest extends FormRequest
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
            'sizes'=>'required|array',
            'sizes.*.size'=>'required',
            'sizes.*.unit'=>'required|string',
            'sizes.*.price'=>'required|numeric',
            'sizes.*.colors.*'=>'exists:colors,id',
            'product_id'=>'required|exists:products,id',
        ];
    }
}
