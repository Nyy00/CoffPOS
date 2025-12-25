<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isManager());
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code,' . ($this->product ? $this->product->id : 'NULL'),
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0|max:999999.99',
            'cost' => 'nullable|numeric|min:0|max:999999.99',
            'stock' => 'required|integer|min:0|max:999999',
            'min_stock' => 'required|integer|min:0|max:999999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'code.required' => 'Product code is required.',
            'code.unique' => 'Product code already exists.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'stock.required' => 'Stock quantity is required.',
            'stock.integer' => 'Stock must be a whole number.',
            'stock.min' => 'Stock cannot be negative.',
            'stock.max' => 'Stock cannot exceed 999,999.',
            'min_stock.required' => 'Minimum stock alert is required.',
            'min_stock.integer' => 'Minimum stock must be a whole number.',
            'min_stock.min' => 'Minimum stock cannot be negative.',
            'image.image' => 'File must be an image.',
            'image.mimes' => 'Image must be in JPEG, PNG, JPG, or GIF format.',
            'image.max' => 'Image size cannot exceed 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'is_available' => 'availability status',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox to boolean
        $this->merge([
            'is_available' => $this->has('is_available') ? true : false,
        ]);

        // Clean numeric inputs
        if ($this->has('price')) {
            $this->merge([
                'price' => (float) str_replace(',', '', $this->price)
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if category is active (if you have status field)
            if ($this->category_id) {
                $category = \App\Models\Category::find($this->category_id);
                if (!$category) {
                    $validator->errors()->add('category_id', 'Selected category is not available.');
                }
            }
        });
    }
}