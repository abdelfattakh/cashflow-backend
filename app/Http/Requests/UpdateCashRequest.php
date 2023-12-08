<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCashRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
            $cash_id = $this->route('cash');
        return [
            'name' => ['sometimes', 'string', 'max:255',Rule::unique('cashes','name')->ignore($cash_id)],
            'actual_balance' => ['sometimes', 'digits_between:1,255'],
        ];
    }
}
