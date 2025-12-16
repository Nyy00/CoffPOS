<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            'name' => 'required|string|max:255|min:2',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'min:10',
                'regex:/^[0-9+\-\s()]+$/',
                Rule::unique('users', 'phone')->ignore($userId)
            ],
            'role' => 'required|in:admin,manager,cashier',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Password rules - required for create, optional for update
        if (!$isUpdate) {
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ];
        } else {
            $rules['password'] = [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.string' => 'Full name must be text.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'name.min' => 'Full name must be at least 2 characters long.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'This email is already registered to another user.',
            'phone.string' => 'Phone number must be text.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.min' => 'Phone number must be at least 10 characters long.',
            'phone.regex' => 'Phone number format is invalid. Use only numbers, +, -, spaces, and parentheses.',
            'phone.unique' => 'This phone number is already registered to another user.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'role.required' => 'User role is required.',
            'role.in' => 'Selected role is invalid. Must be admin, manager, or cashier.',
            'avatar.image' => 'Avatar must be an image.',
            'avatar.mimes' => 'Avatar must be in JPEG, PNG, or JPG format.',
            'avatar.max' => 'Avatar size cannot exceed 2MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'role' => 'user role',
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

        // Clean email
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }

        // Clean phone number
        if ($this->has('phone') && $this->phone) {
            $phone = preg_replace('/[^\d+\-\s()]/', '', $this->phone);
            $this->merge(['phone' => trim($phone)]);
        }

        // Remove password if empty on update
        if (($this->isMethod('PUT') || $this->isMethod('PATCH')) && empty($this->password)) {
            $this->request->remove('password');
            $this->request->remove('password_confirmation');
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Prevent changing own role from admin
            if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
                $user = $this->route('user');
                if ($user && $user->id === auth()->id() && $this->role !== 'admin') {
                    $validator->errors()->add('role', 'You cannot change your own role from admin.');
                }
            }

            // Validate name contains only letters and spaces
            if ($this->name && !preg_match('/^[a-zA-Z\s\.]+$/', $this->name)) {
                $validator->errors()->add('name', 'Full name can only contain letters, spaces, and periods.');
            }

            // Validate Indonesian phone number format
            if ($this->phone) {
                $cleanPhone = preg_replace('/[^\d]/', '', $this->phone);
                
                if (!preg_match('/^(08|628|\+628)/', $this->phone) && !preg_match('/^[0-9]{10,13}$/', $cleanPhone)) {
                    $validator->errors()->add('phone', 'Please enter a valid Indonesian phone number (e.g., 08123456789 or +628123456789).');
                }
            }

            // Ensure at least one admin exists
            if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
                $user = $this->route('user');
                if ($user && $user->role === 'admin' && $this->role !== 'admin') {
                    $adminCount = \App\Models\User::where('role', 'admin')->where('id', '!=', $user->id)->count();
                    if ($adminCount === 0) {
                        $validator->errors()->add('role', 'Cannot change role. At least one admin must exist in the system.');
                    }
                }
            }
        });
    }

    /**
     * Get role options for display
     */
    public static function getRoleOptions(): array
    {
        return [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'cashier' => 'Cashier'
        ];
    }
}