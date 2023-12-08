<?php

namespace App\Http\Requests;

use App\Enums\PriorityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date_from' => ['sometimes','date','date_format:Y-m-d'],
            'date_to' => ['required_with:date_from','date','date_format:Y-m-d'],
            'company_id'=>['sometimes','exists:companies,id'],
            'project_id'=>['sometimes','exists:projects,id'],
            'priority'=>['sometimes',Rule::in(PriorityEnum::toValues())],
            'id'=>['sometimes','exists:items,id'],
            'name'=>['sometimes','string','max:255'],
        ];
    }
}
