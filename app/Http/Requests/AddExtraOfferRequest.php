<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddExtraOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image'=>'required|image',
            'branch_id'=>'required|exists:branches,id',
            'product_id'=>'required|exists:products,id',
            'product_count'=>'required|numeric',
            'extra_count'=>'required|numeric',
        ];
    }
}
