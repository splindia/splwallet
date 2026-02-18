<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class AdjustWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'reference_id' => ['required', 'string', 'max:80'],
            'remark' => ['nullable', 'string', 'max:200'],
        ];
    }
}
