<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdatedSuccessfullyRequest extends FormRequest
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
        $user_id = $this->route('user');

        return [
            'name' => 'sometimes|min:4',
            'email' => ['sometimes','email',Rule::unique('users','email')->ignore($user_id)],
            'password' => 'sometimes|min:8',
            'role_id' =>'sometimes|exists:roles,id',
            "permissions_id"    => "sometimes|array",
            "permissions_id.*"  => "sometimes|int|exists:permissions,id",
        ];
    }
}
