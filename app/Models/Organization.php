<?php

namespace App\Models;


use App\Traits\HasPrimaryImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Organization extends Model implements HasMedia
{
    use HasFactory, HasPrimaryImage;


    protected $fillable = [
        'name'
    ];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * @return HasMany
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    /**
     * @return HasMany
     */
    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class);
    }
}
