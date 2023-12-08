<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_number',
        'current_balance',
        'actual_balance',
        'project_id',
        'company_id',
        'organization_id'

    ];

    protected $casts = [
        'current_balance' => 'float',
        'actual_balance' => 'float',
        'project_id' => 'int',
        'organization_id' => 'int',

    ];


    /**
     * @return HasMany
     */
    public function companies():HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function items():MorphMany
    {
        return $this->morphMany(Item::class,'transactionable');
    }
    /**
     * @return BelongsTo
     */
    public function organization():BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }


}
