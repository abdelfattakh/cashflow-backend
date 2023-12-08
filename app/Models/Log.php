<?php

namespace App\Models;

use App\Traits\LogsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'status',
        'name',
        'value',
        'type',
        'date',
        'priority_level',
        'max_period',
        'description',
        'comment',
        'bank_id',
        'company_id',
        'project_id',
        'status',
        'checked',
    ];
    protected $appends = ["changedBy"];
    public function getChangedByAttribute() {
        if (is_null($this->user_id))
        {
            return 'ai';
        }
         return 'admin';
    }

    protected $casts = [
        'item_id' => 'int',
        'user_id' => 'int',
        'bank_id'=>'int',
        'company_id'=>'int',
        'project_id'=>'int',
        'checked'=>'boolean'
    ];


    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
