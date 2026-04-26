<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'tagline', 'description',
        'price_monthly', 'price_yearly', 'currency', 'save_percent',
        'features', 'cta_label', 'cta_url',
        'is_active', 'is_popular', 'is_free_trial', 'free_trial_days',
        'sort_order',
    ];

    protected $casts = [
        'features'        => 'array',
        'price_monthly'   => 'decimal:2',
        'price_yearly'    => 'decimal:2',
        'is_active'       => 'boolean',
        'is_popular'      => 'boolean',
        'is_free_trial'   => 'boolean',
        'save_percent'    => 'integer',
        'free_trial_days' => 'integer',
        'sort_order'      => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $plan) {
            if (empty($plan->slug) && !empty($plan->name)) {
                $plan->slug = Str::slug($plan->name);
            }
            if (is_string($plan->features)) {
                $decoded = json_decode($plan->features, true);
                if (is_array($decoded)) {
                    $plan->features = $decoded;
                }
            }
        });
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    /** Helper used by the public pricing page to render raw integer amount. */
    public function getMonthlyIntAttribute(): int
    {
        return (int) round((float) $this->price_monthly);
    }

    public function getYearlyIntAttribute(): int
    {
        return (int) round((float) $this->price_yearly);
    }
}
