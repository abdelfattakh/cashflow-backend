<?php

namespace App\Models;

use App\Enums\DateTypeEnum;
use App\Enums\ItemStatusEnum;
use App\Traits\LogsTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory, LogsTrait;

    protected $fillable = [
        'name',
        'type',
        'date',
        'priority_level',
        'max_period',
        'description',
        'comment',
        'user_id',
        'transactionable_id',
        'transactionable_type',
        'company_id',
        'project_id',
        'status',
        'checked',
        'value',
        'organization_id'

    ];
    protected $casts = [
        'user_id' => 'int',
        'organization_id' => 'int',
        'bank_id' => 'int',
        'company_id' => 'int',
        'project_id' => 'int',
        'checked' => 'boolean',
        'value' => 'float'
    ];


    protected static function boot()
    {
        parent::boot();
        static::created(function (self $model) {
            $model->status = ItemStatusEnum::pending()->value;
            $model->save();
        });
        static::updating(function (self $model) {
            self::track($model);

        });

    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * @return BelongsTo
     */
    public function cash(): BelongsTo
    {
        return $this->belongsTo(Cash::class);
    }

    /**
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    /**
     * @return MorphTo
     */
    public function transactionable():MorphTo
    {
        return $this->morphTo();
    }

}
