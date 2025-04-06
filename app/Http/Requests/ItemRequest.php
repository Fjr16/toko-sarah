<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'code' => 'required|unique:items,code',
            'name' => 'required|string|unique:items,name',
            'small_unit' => 'required|string',
            'medium_unit' => 'nullable|string',
            'medium_to_small' => 'required_with:medium_unit',
            'big_unit' => 'nullable|string',
            'big_to_medium' => 'required_with:big_unit',
            'cost' => 'required',
            'price' => 'required',
            'stok' => 'required|integer',
            'stok_alert' => 'required|integer',
            'tax' => 'required|integer',
            'tax_type' => 'required|in:exclusive,inclusive,none',
            'note' => 'nullable|string',
        ];
    }
}
