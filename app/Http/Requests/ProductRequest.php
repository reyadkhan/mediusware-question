<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:150',
            'sku' => [
                'required', 'string', 'regex:/^\S*$/u',
                Rule::unique('products')->ignore($this->id ?? optional($this->product)->id)
            ],
            'description' => 'nullable|string|max:2000',
            'product_image' => 'nullable|array',
            'product_image.*.file_path' => 'nullable|string',
            'product_variant' => 'required|array|max:3',
            'product_variant.*.option' => 'required|exists:variants,id',
            'product_variant_prices' => 'required|array',
            'product_variant_prices.*.price' => 'required|numeric|min:0',
            'product_variant_prices.*.stock' => 'required|numeric|min:0'
        ];
    }
}
