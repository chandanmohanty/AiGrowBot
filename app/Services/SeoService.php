<?php

namespace App\Services;

use App\Models\Post;
use App\Models\SeoMeta;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SeoService
{
    public function forRoute(string $routeKey, array $overrides = []): array
    {
        $row = Cache::remember("seo:route:{$routeKey}", 600, fn () =>
            SeoMeta::where('route_key', $routeKey)->first()
        );

        $base = [
            'title'       => $row?->title ?? config('seo.site_name'),
            'description' => $row?->description ?? config('seo.description'),
            'keywords'    => $row?->keywords,
            'og_image'    => $row?->og_image ?: config('seo.default_image'),
            'canonical'   => $row?->canonical_url ?: url()->current(),
            'noindex'     => (bool) ($row?->noindex ?? false),
            'og_type'     => 'website',
        ];

        return array_merge($base, $overrides);
    }

    public function forPost(Post $post): array
    {
        return [
            'title'       => $post->metaTitle(),
            'description' => $post->metaDescription(),
            'keywords'    => $post->meta_keywords,
            'og_image'    => $post->og_image ?: $post->cover_image ?: config('seo.default_image'),
            'canonical'   => $post->canonical_url ?: url()->current(),
            'noindex'     => (bool) $post->noindex,
            'og_type'     => 'article',
        ];
    }

    public static function flush(): void
    {
        Cache::flush();
    }
}
