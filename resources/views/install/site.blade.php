@extends('install.layout', ['pageTitle' => 'Site settings'])

@section('install-content')
    <h2>Site settings</h2>
    <p class="install-lead">These values are stored in the database and used site-wide. You can edit them later in Admin → Settings.</p>

    <form method="POST" action="{{ route('install.site.save') }}" class="install-form">
        @csrf

        <div class="grid2">
            <div class="field">
                <label>Site name *</label>
                <input type="text" name="site_name" required value="{{ old('site_name', $data['site_name']) }}">
            </div>
            <div class="field">
                <label>Tagline</label>
                <input type="text" name="site_tagline" value="{{ old('site_tagline', $data['site_tagline']) }}">
            </div>
        </div>

        <div class="field">
            <label>Public URL *</label>
            <input type="url" name="app_url" required value="{{ old('app_url', $data['app_url']) }}" placeholder="https://www.your-domain.com">
        </div>

        <div class="grid2">
            <div class="field">
                <label>Contact email *</label>
                <input type="email" name="contact_email" required value="{{ old('contact_email', $data['contact_email']) }}">
            </div>
            <div class="field">
                <label>Contact phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $data['contact_phone']) }}">
            </div>
        </div>

        <div class="grid2">
            <div class="field">
                <label>WhatsApp number <small>(digits only)</small></label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $data['whatsapp_number']) }}" placeholder="919870217934">
            </div>
            <div class="field">
                <label>Timezone *</label>
                @php
                    $zones = ['Asia/Kolkata','UTC','Asia/Dubai','Asia/Singapore','Europe/London','America/New_York','America/Los_Angeles'];
                @endphp
                <select name="timezone" required>
                    @foreach ($zones as $tz)
                        <option value="{{ $tz }}" {{ old('timezone', $data['timezone']) === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="install-actions">
            <a href="{{ route('install.database') }}" class="btn-ghost"><i class="fa-solid fa-arrow-left"></i> Back</a>
            <button type="submit" class="btn-next">Continue <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </form>
@endsection
