<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
        return [
            //
            'name' =>  ['required','unique:banks,name','string','max:255'],
            'account_number' => ['required','digits_between:7,255','unique:banks,account_number','min:1'],
            'actual_balance' => ['required','digits_between:1,255']

        ];
    }
}
