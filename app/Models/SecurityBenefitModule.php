<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecurityBenefitModule extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'subtitle',
        'sort_order',
        'is_manual',
    ];

    protected $casts = [
        'is_manual' => 'boolean',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(SecurityBenefitCategory::class, 'module_id')->orderBy('sort_order');
    }
}
