<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'shipping_first_name' => ['required', 'string', 'max:255'],
            'shipping_last_name' => ['required', 'string', 'max:255'],
            'shipping_address_line1' => ['required', 'string', 'max:255'],
            'shipping_address_line2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:255'],
            'shipping_state' => ['nullable', 'string', 'max:255'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:255'],
            'shipping_phone' => ['nullable', 'string', 'max:50'],
            'billing_same_as_shipping' => ['sometimes', 'boolean'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'payment_method' => ['required', 'string', 'in:online,cash_on_delivery'],
            'transaction_id' => ['required_if:payment_method,online', 'nullable', 'string', 'max:255'],
            'payment_channel' => ['required_if:payment_method,online', 'nullable', 'string', 'in:easypaisa,jazzcash,bank_account'],
            'payment_screenshot' => ['required_if:payment_method,online', 'nullable', 'image', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];

        if (!$this->boolean('billing_same_as_shipping')) {
            $rules = array_merge($rules, [
                'billing_first_name' => ['required', 'string', 'max:255'],
                'billing_last_name' => ['required', 'string', 'max:255'],
                'billing_address_line1' => ['required', 'string', 'max:255'],
                'billing_address_line2' => ['nullable', 'string', 'max:255'],
                'billing_city' => ['required', 'string', 'max:255'],
                'billing_state' => ['nullable', 'string', 'max:255'],
                'billing_postal_code' => ['required', 'string', 'max:20'],
                'billing_country' => ['required', 'string', 'max:255'],
                'billing_phone' => ['nullable', 'string', 'max:50'],
            ]);
        }

        return $rules;
    }
}
