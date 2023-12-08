<?php

namespace App\Http\Requests;

use App\Models\Bank;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $bank_id = $this->route('bank');
        return [
            'name' => ['sometimes', 'string', 'max:255',Rule::unique('banks','name')->ignore($bank_id)],
            'account_number' => ['sometimes', 'digits_between:7,255',Rule::unique('banks','account_number')->ignore($bank_id)],
            'actual_balance' => ['sometimes', 'digits_between:1,255'],
        ];
    }
}
