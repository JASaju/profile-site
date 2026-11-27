<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityBenefit extends Model
{
    protected $fillable = [
        'category_id',
        'slug',
        'label',
        'title',
        'description',
        'potential_value',
        'context',
        'sort_order',
        'is_manual',
    ];

    protected $casts = [
        'context' => 'array',
        'is_manual' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SecurityBenefitCategory::class, 'category_id');
    }
}
