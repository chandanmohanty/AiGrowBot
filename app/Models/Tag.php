<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::saving(function (self $t) {
            if (empty($t->slug)) {
                $t->slug = Str::slug($t->name);
            }
        });
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
