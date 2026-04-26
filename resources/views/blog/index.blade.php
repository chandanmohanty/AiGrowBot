@extends('layouts.app')

@section('content')
<section class="section" style="padding-top:140px;min-height:70vh">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Blog</span>
            <h2>Insights &amp; <span class="gradient-text">Tutorials</span></h2>
            <p>Latest articles, product updates and growth tips from the AI Grow Bot team.</p>
        </div>

        <form method="GET" style="max-width:600px;margin:0 auto 40px;display:flex;gap:8px">
            <input type="search" name="q" value="{{ $q }}" placeholder="Search articles..." style="flex:1;padding:14px 18px;border-radius:999px;border:1px solid #e5e7eb;font-size:15px">
            <button class="btn btn-primary btn-glow" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <div class="grid grid-3" style="gap:24px">
            @forelse ($posts as $post)
                <article class="feature-card reveal" style="display:flex;flex-direction:column;padding:0;overflow:hidden">
                    @if ($post->cover_image)
                        <a href="{{ route('blog.show', $post->slug) }}">
                            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" loading="lazy" decoding="async" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block">
                        </a>
                    @endif
                    <div style="padding:24px;display:flex;flex-direction:column;gap:12px;flex:1">
                        @if ($post->category)
                            <span class="eyebrow" style="align-self:flex-start">{{ $post->category->name }}</span>
                        @endif
                        <h3 style="margin:0;font-size:20px;line-height:1.3">
                            <a href="{{ route('blog.show', $post->slug) }}" style="color:inherit;text-decoration:none">{{ $post->title }}</a>
                        </h3>
                        <p style="color:#64748b;margin:0;flex:1">{{ $post->excerpt ?: Str::limit(strip_tags($post->body), 140) }}</p>
                        <div style="display:flex;align-items:center;gap:10px;color:#94a3b8;font-size:13px">
                            <span>{{ $post->author?->name }}</span>
                            <span>&middot;</span>
                            <time datetime="{{ optional($post->published_at)->toIso8601String() }}">{{ optional($post->published_at)->format('M d, Y') }}</time>
                        </div>
                    </div>
                </article>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:#94a3b8">
                    <i class="fa-solid fa-newspaper" style="font-size:48px;margin-bottom:16px;display:block"></i>
                    <p>No posts yet. Check back soon!</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top:40px;display:flex;justify-content:center">{{ $posts->links() }}</div>
    </div>
</section>
@endsection
