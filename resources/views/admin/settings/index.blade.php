@extends('layouts.admin')

@section('admin-content')
<div class="page-head"><h1 class="page-title">Site Settings</h1></div>

@if ($errors->any())<div class="alert alert-danger">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    <div class="card">
        <h3 style="margin-top:0">General</h3>
        <div class="form-row">
            <div class="form-group"><label>Site Name</label><input type="text" name="site_name" value="{{ $values['site_name'] }}"></div>
            <div class="form-group"><label>Tagline</label><input type="text" name="site_tagline" value="{{ $values['site_tagline'] }}"></div>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Contact</h3>
        <div class="form-row">
            <div class="form-group"><label>Email</label><input type="email" name="contact_email" value="{{ $values['contact_email'] }}"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="contact_phone" value="{{ $values['contact_phone'] }}"></div>
        </div>
        <div class="form-group"><label>WhatsApp Number <span class="help">(digits only, with country code e.g. 919870217934)</span></label><input type="text" name="whatsapp_number" value="{{ $values['whatsapp_number'] }}"></div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Analytics &amp; Verification</h3>
        <div class="form-row">
            <div class="form-group"><label>Google Analytics (GA4 ID)</label><input type="text" name="google_analytics" value="{{ $values['google_analytics'] }}" placeholder="G-XXXXXXX"></div>
            <div class="form-group"><label>Facebook Pixel ID</label><input type="text" name="facebook_pixel" value="{{ $values['facebook_pixel'] }}"></div>
        </div>
        <div class="form-group"><label>Google Site Verification Code</label><input type="text" name="google_verify" value="{{ $values['google_verify'] }}"></div>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Social Links</h3>
        <p class="help" style="margin-top:-6px;margin-bottom:14px">
            Paste the full profile URL. Leave blank to hide the icon across the site (header, footer &amp; contact).
        </p>

        @php
            $socials = [
                ['key' => 'social_facebook',  'label' => 'Facebook',  'icon' => 'fa-brands fa-facebook-f', 'color' => '#1877f2', 'placeholder' => 'https://www.facebook.com/your-page'],
                ['key' => 'social_youtube',   'label' => 'YouTube',   'icon' => 'fa-brands fa-youtube',    'color' => '#ff0000', 'placeholder' => 'https://www.youtube.com/@your-channel'],
                ['key' => 'social_linkedin',  'label' => 'LinkedIn',  'icon' => 'fa-brands fa-linkedin-in','color' => '#0a66c2', 'placeholder' => 'https://www.linkedin.com/company/your-org'],
                ['key' => 'social_instagram', 'label' => 'Instagram', 'icon' => 'fa-brands fa-instagram',  'color' => '#e4405f', 'placeholder' => 'https://www.instagram.com/your-handle'],
                ['key' => 'social_twitter',   'label' => 'X / Twitter','icon'=> 'fa-brands fa-x-twitter',  'color' => '#111',    'placeholder' => 'https://x.com/your-handle'],
            ];
        @endphp

        <div class="social-grid">
            @foreach ($socials as $s)
                <div class="social-field">
                    <label>{{ $s['label'] }}</label>
                    <div class="social-input">
                        <span class="social-input-icon" style="background:{{ $s['color'] }}"><i class="{{ $s['icon'] }}"></i></span>
                        <input type="url" name="{{ $s['key'] }}"
                               value="{{ $values[$s['key']] ?? '' }}"
                               placeholder="{{ $s['placeholder'] }}">
                        @if (!empty($values[$s['key']]))
                            <a href="{{ $values[$s['key']] }}" target="_blank" rel="noopener" class="social-test" title="Open in new tab">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
    .social-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:14px}
    .social-field label{display:block;font-weight:600;font-size:12.5px;margin-bottom:6px;color:#374151}
    .social-input{display:flex;align-items:stretch;border:1px solid var(--border);border-radius:8px;overflow:hidden;background:#fff;transition:border-color .2s,box-shadow .2s}
    .social-input:focus-within{border-color:#ff6b35;box-shadow:0 0 0 3px rgba(255,107,53,.12)}
    .social-input-icon{display:inline-flex;align-items:center;justify-content:center;width:42px;color:#fff;font-size:14px;flex-shrink:0}
    .social-input input{flex:1;border:0;outline:0;padding:10px 12px;font-size:13.5px;font-family:inherit;background:transparent}
    .social-test{display:inline-flex;align-items:center;justify-content:center;width:40px;color:#6b7280;text-decoration:none;border-left:1px solid var(--border);transition:color .2s,background .2s}
    .social-test:hover{color:#ff6b35;background:#fff5ee}
    </style>

    <button class="btn btn-primary-admin">Save Settings</button>
</form>
@endsection
