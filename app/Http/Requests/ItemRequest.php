<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ItemRequest extends FormRequest
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
            'item_category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string',
            'small_unit' => 'required|string',
            'medium_unit' => 'nullable|string',
            'big_unit' => 'nullable|string',
            'medium_to_small' => 'required_with:medium_unit',
            'big_to_medium' => 'required_with:big_unit',
            'cost' => 'required',
            'margin' => 'required',
            'price' => 'required',
            'stok' => 'required|integer',
            'stok_alert' => 'required|integer',
            'image' => 'nullable|file|image:png,jpg,jpeg,webp',
            'description' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ])
        );
    }
}
