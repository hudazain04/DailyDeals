<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPercentageOfferRequest extends FormRequest
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
            'image'=>'required|image',
            'branch_id'=>'required|exists:branches,id',
            'percentage'=>'required|numeric',
            'products'=>'required|array',
            'products.*.product'=>'exists:products,id',


        ];
    }
}
