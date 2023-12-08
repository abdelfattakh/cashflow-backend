<?php

namespace App\Traits;


use App\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait LogsTrait
{
    public static function track(Model $model)
    {
        $data = collect($model->getDirty())->filter(function ($value, $key) {
            return !in_array($key, ['created_at', 'updated_at']);
        })->mapWithKeys(function ($value, $key) {
            // Take the field names and convert them into human readable strings for the description of the action
            return [str_replace('_', ' ', $key) => $value];

        });
        $originalData = collect($model->getOriginal())->filter(function ($value, $key) {
            return !in_array($key, ['created_at', 'updated_at']);
        })->mapWithKeys(function ($value, $key) {
            // Take the field names and convert them into human readable strings for the description of the action
            return [str_replace('_', ' ', $key) => $value];
        })->toArray();
        if (request()->getMethod() == 'PUT') {
            Log::query()->create([
                'item_id' => $originalData['id'],
                'user_id' => Auth::id(),
                'name' => $originalData['name'] ?? null,
                'value' => $originalData['value'] ?? null,
                'type' => $originalData['type'] ?? null,
                'date' => $originalData['date'] ?? null,
                'priority_level' => $originalData['priority_level'] ?? null,
                'max_period' => $originalData['max_period'] ?? null,
                'description' => $originalData['description'] ?? null,
                'comment' => $originalData['comment'] ?? null,
                'bank_id' => $originalData['bank_id'] ?? null,
                'company_id' => $originalData['company_id'] ?? null,
                'project_id' => $originalData['project_id'] ?? null,
                'status' => $originalData['status'] ?? null,
                'checked' => $originalData['checked'] ?? null,
            ]);
        }


    }
}
