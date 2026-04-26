@extends('layouts.app')

@section('content')
<section class="section" style="padding-top:140px;min-height:60vh">
    <div class="container" style="max-width:820px">
        <h1>Privacy Policy</h1>
        <p>Last updated: {{ now()->format('F d, Y') }}</p>
        <p>This is a placeholder privacy policy. Update it from the admin panel (SEO entry <code>page.privacy</code>) or edit <code>resources/views/pages/privacy.blade.php</code>.</p>
    </div>
</section>
@endsection
