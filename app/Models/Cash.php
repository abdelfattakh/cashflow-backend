<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Cash extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'current_balance',
        'actual_balance',
        'organization_id'

    ];

    protected $casts = [
        'current_balance' => 'float',
        'actual_balance' => 'float',
        'organization_id' => 'int',

    ];

    /**
     * @return HasMany
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }


    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function items(): MorphMany
    {
        return $this->morphMany(Item::class, 'transactionable');
    }
}
