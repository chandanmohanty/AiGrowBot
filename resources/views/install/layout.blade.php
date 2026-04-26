<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $pageTitle ?? 'Setup Wizard' }} — AI Grow Bot</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ai-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('install.css') }}?v={{ @filemtime(public_path('install.css')) ?: time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="install-body">
<div class="install-stage">
    <span class="install-blob b1"></span>
    <span class="install-blob b2"></span>
    <span class="install-blob b3"></span>

    <div class="install-shell">
        <header class="install-head">
            <div class="install-brand">
                <img src="{{ asset('img/aiGrowBot_black.png') }}" alt="AI Grow Bot">
            </div>
            <div class="install-title">
                <h1>AI Grow Bot — Setup Wizard</h1>
                <p>Complete these steps once to deploy the site on this server.</p>
            </div>
        </header>

        @php
            $steps = [
                1 => ['Requirements', 'fa-list-check'],
                2 => ['Database',     'fa-database'],
                3 => ['Site',         'fa-globe'],
                4 => ['Admin',        'fa-user-shield'],
                5 => ['Install',      'fa-rocket'],
                6 => ['Done',         'fa-circle-check'],
            ];
            $current = (int) ($stepIndex ?? 1);
        @endphp
        <ol class="install-steps">
            @foreach ($steps as $i => [$label, $icon])
                <li class="step {{ $i < $current ? 'done' : ($i === $current ? 'active' : '') }}">
                    <span class="step-num"><i class="fa-solid {{ $icon }}"></i></span>
                    <span class="step-label">{{ $label }}</span>
                </li>
            @endforeach
        </ol>

        @if ($errors->any())
            <div class="install-alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
        @endif

        <main class="install-card">
            @yield('install-content')
        </main>

        <footer class="install-foot">
            <span>&copy; {{ date('Y') }} AI Grow Bot. Secured installer &middot; locks itself after setup.</span>
        </footer>
    </div>
</div>
</body>
</html>
