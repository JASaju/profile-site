<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecurityBenefitCategory extends Model
{
    protected $fillable = [
        'module_id',
        'slug',
        'name',
        'description',
        'content_type',
        'sort_order',
        'is_manual',
    ];

    protected $casts = [
        'is_manual' => 'boolean',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(SecurityBenefitModule::class, 'module_id');
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(SecurityBenefit::class, 'category_id')->orderBy('sort_order');
    }
}
