@extends('layouts.app')

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    'name'     => config('seo.site_name'),
    'url'      => config('seo.domain'),
    'potentialAction' => [
        '@type'       => 'SearchAction',
        'target'      => config('seo.domain').'/blog?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
@include('home.hero')
@include('home.trust')
@include('home.features')
@include('home.preview')
@include('home.wa-showcase')
@include('home.how')
@include('home.benefits')
@include('home.pricing')
@include('home.video')
@include('home.testimonials')
@include('home.clients')
@include('home.contact')
@include('home.faq')
@include('home.cta')
@endsection
