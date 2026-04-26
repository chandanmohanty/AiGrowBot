<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="theme-color" content="#0f172a" />

{{-- SEO --}}
@php
    $seoTitle       = $seo['title']       ?? config('seo.site_name');
    $seoDescription = $seo['description'] ?? config('seo.description');
    $seoKeywords    = $seo['keywords']    ?? null;
    $seoImage       = $seo['og_image']    ?? config('seo.default_image');
    $seoCanonical   = $seo['canonical']   ?? url()->current();
    $seoNoindex     = $seo['noindex']     ?? false;
    $seoType        = $seo['og_type']     ?? 'website';
    $absImage       = Str::startsWith($seoImage, ['http://', 'https://']) ? $seoImage : url($seoImage);
@endphp

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}" />
@if ($seoKeywords)
    <meta name="keywords" content="{{ $seoKeywords }}" />
@endif
@if ($seoNoindex)
    <meta name="robots" content="noindex, nofollow" />
@else
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1" />
@endif
<link rel="canonical" href="{{ $seoCanonical }}" />

{{-- Open Graph --}}
<meta property="og:type" content="{{ $seoType }}" />
<meta property="og:site_name" content="{{ config('seo.site_name') }}" />
<meta property="og:title" content="{{ $seoTitle }}" />
<meta property="og:description" content="{{ $seoDescription }}" />
<meta property="og:url" content="{{ $seoCanonical }}" />
<meta property="og:image" content="{{ $absImage }}" />
<meta property="og:locale" content="{{ config('seo.locale') }}" />

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $seoTitle }}" />
<meta name="twitter:description" content="{{ $seoDescription }}" />
<meta name="twitter:image" content="{{ $absImage }}" />
@if (config('seo.twitter'))
    <meta name="twitter:site" content="{{ config('seo.twitter') }}" />
@endif

@if ($verify = \App\Models\Setting::get('google_verify'))
    <meta name="google-site-verification" content="{{ $verify }}" />
@endif

{{-- Preconnect for speed --}}
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />

<link rel="icon" type="image/png" href="{{ asset('img/ai-logo.png') }}" />
<link rel="apple-touch-icon" href="{{ asset('img/ai-logo.png') }}" />

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
<link rel="stylesheet" href="{{ asset('styles.css') }}?v={{ filemtime(public_path('styles.css')) }}" />

{{-- JSON-LD Organization --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Organization',
    'name'     => config('seo.organization.name'),
    'url'      => config('seo.domain'),
    'logo'     => url(config('seo.organization.logo')),
    'email'    => config('seo.organization.email'),
    'telephone'=> config('seo.organization.phone'),
    'sameAs'   => config('seo.organization.sameAs'),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>

@stack('head')

@php $ga = \App\Models\Setting::get('google_analytics'); @endphp
@if ($ga)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $ga }}');</script>
@endif
