<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'cover_image',
        'excerpt', 'body', 'status', 'published_at',
        'meta_title', 'meta_description', 'meta_keywords',
        'og_image', 'canonical_url', 'noindex',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'noindex'      => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $p) {
            if (empty($p->slug)) {
                $p->slug = Str::slug($p->title);
            }
            if ($p->status === 'published' && empty($p->published_at)) {
                $p->published_at = now();
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published')
                 ->where(function ($q) {
                     $q->whereNull('published_at')->orWhere('published_at', '<=', now());
                 });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function metaTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function metaDescription(): string
    {
        return $this->meta_description ?: Str::limit(strip_tags((string) $this->excerpt ?: $this->body), 160);
    }
}
