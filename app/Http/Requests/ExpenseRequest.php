<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ExpenseRequest extends FormRequest
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
        return [
            'category' => 'required|in:inventory,operational,salary,utilities,marketing,maintenance,other',
            'description' => 'required|string|max:500|min:5',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'expense_date' => 'required|date|before_or_equal:today|after_or_equal:' . Carbon::now()->subYear()->format('Y-m-d'),
            'receipt_image' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120' // 5MB max
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Expense category is required.',
            'category.in' => 'Selected category is invalid.',
            'description.required' => 'Expense description is required.',
            'description.string' => 'Description must be text.',
            'description.max' => 'Description cannot exceed 500 characters.',
            'description.min' => 'Description must be at least 5 characters long.',
            'amount.required' => 'Expense amount is required.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be at least 0.01 (1 cent).',
            'amount.max' => 'Amount cannot exceed 999,999.99.',
            'expense_date.required' => 'Expense date is required.',
            'expense_date.date' => 'Please enter a valid date.',
            'expense_date.before_or_equal' => 'Expense date cannot be in the future.',
            'expense_date.after_or_equal' => 'Expense date cannot be more than 1 year ago.',
            'receipt_image.file' => 'Receipt must be a valid file.',
            'receipt_image.mimes' => 'Receipt must be in JPEG, PNG, JPG, or PDF format.',
            'receipt_image.max' => 'Receipt file size cannot exceed 5MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category' => 'expense category',
            'description' => 'expense description',
            'amount' => 'expense amount',
            'expense_date' => 'expense date',
            'receipt_image' => 'receipt file',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean amount input
        if ($this->has('amount')) {
            $amount = str_replace([',', ' '], '', $this->amount);
            $this->merge(['amount' => (float) $amount]);
        }

        // Format date if needed
        if ($this->has('expense_date') && $this->expense_date) {
            try {
                $date = Carbon::parse($this->expense_date)->format('Y-m-d');
                $this->merge(['expense_date' => $date]);
            } catch (\Exception $e) {
                // Let validation handle invalid date
            }
        }

        // Clean description
        if ($this->has('description')) {
            $this->merge([
                'description' => trim($this->description)
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if amount is reasonable for the category
            if ($this->amount && $this->category) {
                $maxAmounts = [
                    'inventory' => 50000000, // 50M for inventory
                    'operational' => 10000000, // 10M for operational
                    'salary' => 20000000, // 20M for salary
                    'utilities' => 5000000, // 5M for utilities
                    'marketing' => 10000000, // 10M for marketing
                    'maintenance' => 5000000, // 5M for maintenance
                    'other' => 10000000 // 10M for other
                ];

                if (isset($maxAmounts[$this->category]) && $this->amount > $maxAmounts[$this->category]) {
                    $validator->errors()->add('amount', 'Amount seems too high for this category. Please verify the amount.');
                }
            }

            // Check if description is meaningful
            if ($this->description) {
                $commonWords = ['expense', 'cost', 'payment', 'buy', 'purchase'];
                $hasDetail = false;
                
                foreach ($commonWords as $word) {
                    if (stripos($this->description, $word) !== false && strlen($this->description) > 20) {
                        $hasDetail = true;
                        break;
                    }
                }

                if (!$hasDetail && strlen($this->description) < 10) {
                    $validator->errors()->add('description', 'Please provide a more detailed description of the expense.');
                }
            }

            // Validate weekend expenses for certain categories
            if ($this->expense_date && in_array($this->category, ['salary', 'utilities'])) {
                $date = Carbon::parse($this->expense_date);
                if ($date->isWeekend()) {
                    $validator->errors()->add('expense_date', 'Salary and utility expenses are typically not processed on weekends. Please verify the date.');
                }
            }
        });
    }

    /**
     * Get the category options for display
     */
    public static function getCategoryOptions(): array
    {
        return [
            'inventory' => 'Inventory & Supplies',
            'operational' => 'Operational Costs',
            'salary' => 'Salary & Benefits',
            'utilities' => 'Utilities (Electric, Water, Internet)',
            'marketing' => 'Marketing & Advertising',
            'maintenance' => 'Maintenance & Repairs',
            'other' => 'Other Expenses'
        ];
    }

    /**
     * Get category descriptions for help text
     */
    public static function getCategoryDescriptions(): array
    {
        return [
            'inventory' => 'Coffee beans, cups, napkins, food ingredients, packaging materials',
            'operational' => 'Rent, insurance, licenses, permits, professional services',
            'salary' => 'Staff wages, benefits, bonuses, training costs',
            'utilities' => 'Electricity, water, gas, internet, phone bills',
            'marketing' => 'Advertising, promotions, social media, website costs',
            'maintenance' => 'Equipment repairs, cleaning supplies, facility maintenance',
            'other' => 'Miscellaneous expenses that don\'t fit other categories'
        ];
    }
}