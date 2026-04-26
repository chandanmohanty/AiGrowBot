<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'route_key', 'title', 'description', 'keywords',
        'og_image', 'canonical_url', 'noindex',
    ];

    protected $casts = ['noindex' => 'boolean'];
}
