<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => ['required', 'exists:campaigns,id'],
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['required', 'email', 'max:255'],
            'donor_phone' => ['required', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'payment_method' => ['required', 'in:senangpay,manual'],
            'receipt_image' => ['required_if:payment_method,manual', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
