<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        $customerId = $this->route('customer') ? $this->route('customer')->id : null;

        return [
            'name' => 'required|string|max:255|min:2',
            'phone' => [
                'required',
                'string',
                'max:20',
                'min:10',
                'regex:/^[0-9+\-\s()]+$/',
                Rule::unique('customers', 'phone')->ignore($customerId)
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customerId)
            ],
            'address' => 'nullable|string|max:500',
            'points' => 'nullable|integer|min:0|max:999999'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Customer name is required.',
            'name.string' => 'Customer name must be text.',
            'name.max' => 'Customer name cannot exceed 255 characters.',
            'name.min' => 'Customer name must be at least 2 characters long.',
            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be text.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.min' => 'Phone number must be at least 10 characters long.',
            'phone.regex' => 'Phone number format is invalid. Use only numbers, +, -, spaces, and parentheses.',
            'phone.unique' => 'This phone number is already registered to another customer.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'This email is already registered to another customer.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'points.integer' => 'Points must be a whole number.',
            'points.min' => 'Points cannot be negative.',
            'points.max' => 'Points cannot exceed 999,999.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'customer name',
            'phone' => 'phone number',
            'email' => 'email address',
            'points' => 'loyalty points',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and format name
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->name)
            ]);
        }

        // Clean phone number
        if ($this->has('phone')) {
            $phone = preg_replace('/[^\d+\-\s()]/', '', $this->phone);
            $this->merge(['phone' => trim($phone)]);
        }

        // Clean email
        if ($this->has('email') && $this->email) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }

        // Set default points if not provided
        if (!$this->has('points') || $this->points === '') {
            $this->merge(['points' => 0]);
        }

        // Clean address
        if ($this->has('address')) {
            $this->merge([
                'address' => $this->address ? trim($this->address) : null
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate Indonesian phone number format
            if ($this->phone) {
                $cleanPhone = preg_replace('/[^\d]/', '', $this->phone);
                
                // Check if it's Indonesian format
                if (!preg_match('/^(08|628|\+628)/', $this->phone) && !preg_match('/^[0-9]{10,13}$/', $cleanPhone)) {
                    $validator->errors()->add('phone', 'Please enter a valid Indonesian phone number (e.g., 08123456789 or +628123456789).');
                }
            }

            // Validate name contains only letters and spaces
            if ($this->name && !preg_match('/^[a-zA-Z\s\.]+$/', $this->name)) {
                $validator->errors()->add('name', 'Customer name can only contain letters, spaces, and periods.');
            }

            // Check if email domain is valid (basic check)
            if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $validator->errors()->add('email', 'Please enter a valid email address.');
            }
        });
    }
}