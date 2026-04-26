@extends('layouts.app')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BlogPosting',
    'headline' => $post->title,
    'image'    => $post->cover_image ? url($post->cover_image) : url(config('seo.default_image')),
    'datePublished' => optional($post->published_at)->toIso8601String(),
    'dateModified'  => $post->updated_at->toIso8601String(),
    'author'   => ['@type' => 'Person', 'name' => $post->author?->name],
    'publisher' => ['@type' => 'Organization', 'name' => config('seo.site_name'), 'logo' => ['@type' => 'ImageObject', 'url' => url(config('seo.organization.logo'))]],
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
    'description' => $post->metaDescription(),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<article class="section" style="padding-top:140px">
    <div class="container" style="max-width:820px">
        <nav aria-label="Breadcrumb" style="font-size:13px;color:#94a3b8;margin-bottom:24px">
            <a href="{{ url('/') }}" style="color:inherit">Home</a> &middot;
            <a href="{{ route('blog.index') }}" style="color:inherit">Blog</a>
            @if ($post->category) &middot; <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" style="color:inherit">{{ $post->category->name }}</a>@endif
        </nav>

        <h1 style="font-size:42px;line-height:1.15;margin:0 0 16px">{{ $post->title }}</h1>
        <div style="display:flex;align-items:center;gap:12px;color:#64748b;font-size:14px;margin-bottom:32px">
            <strong>{{ $post->author?->name }}</strong>
            <span>&middot;</span>
            <time datetime="{{ optional($post->published_at)->toIso8601String() }}">{{ optional($post->published_at)->format('F d, Y') }}</time>
            <span>&middot;</span>
            <span>{{ max(1, (int) ceil(str_word_count(strip_tags($post->body)) / 200)) }} min read</span>
        </div>

        @if ($post->cover_image)
            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" style="width:100%;border-radius:16px;margin-bottom:32px" loading="eager" fetchpriority="high">
        @endif

        <div class="post-content" style="font-size:18px;line-height:1.8;color:#1e293b">
            {!! $post->body !!}
        </div>

        @if ($post->tags->isNotEmpty())
            <div style="margin-top:40px;padding-top:24px;border-top:1px solid #e5e7eb">
                <strong>Tags:</strong>
                @foreach ($post->tags as $tag)
                    <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="pill" style="margin:4px;display:inline-block">#{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif
    </div>

    @if ($related->isNotEmpty())
        <div class="container" style="margin-top:80px">
            <h3 style="font-size:24px;margin-bottom:24px">Related posts</h3>
            <div class="grid grid-3">
                @foreach ($related as $r)
                    <article class="feature-card reveal visible" style="padding:20px">
                        <h3 style="font-size:17px"><a href="{{ route('blog.show', $r->slug) }}" style="color:inherit;text-decoration:none">{{ $r->title }}</a></h3>
                        <p style="color:#64748b;margin:0">{{ Str::limit(strip_tags($r->excerpt ?: $r->body), 100) }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
</article>
@endsection
