<?php

namespace App\Http\Requests;

use App\Enums\DateTypeEnum;
use App\Models\Bank;
use App\Models\Cash;
use App\Rules\ItemTransableExistRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true ;
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
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'type' => ['required','string','max:255',Rule::in(DateTypeEnum::toValues())],
            'date' => ['required','date','date_format:Y-m-d'],
            'priority_level' => 'required|in:medium,high,low',
            'max_period' => 'required|integer',
            'description' => 'nullable|string',
            'comment' => 'nullable|string',
            'project_id' => ['required','integer','exists:projects,id'],
            'transactionable' => ['required','string',Rule::in(['cash','bank'])],
            'transactionable_id' => ['required_with:transactionable','integer'],
            'company_id' => ['required','integer','exists:companies,id'],

        ];
    }
    public function withValidator($validator)
       {
           $request = $this;
    
           $validator->after(function ($validator) use ($request) {
            if (request('transactionable') == 'cash') {
                $existsInCash = Cash::where('id', request('transactionable_id'))
                    ->exists();
    
                if (!$existsInCash) {
                    $validator->errors()->add('The Cash Id does not exist in any table.');
                }
            }
            if (request('transactionable') == 'bank') {
                $existsInBank = Bank::where('id', request('transactionable_id'))
                    ->exists();
    
                if (!$existsInBank) {
                    $validator->errors()->add('The Bank Id does not exist in any table.');
                }
            }
           });
       }
}
