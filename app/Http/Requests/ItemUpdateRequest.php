<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
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
            'name' => 'string|max:255',
            'value' => 'numeric',
            'type' => 'string|max:255',
            'date' => 'date',
            'priority_level' => 'in:medium,high,low',
            'max_period' => 'integer',
            'description' => 'string',
            'comment' => 'string',
            'project_id' => 'integer',
            'bank_id' => 'integer',
            'company_id' => 'integer',
        ];
    }
}
