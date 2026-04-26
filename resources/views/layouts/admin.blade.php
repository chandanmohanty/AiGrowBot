<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $title ?? 'Admin' }} — {{ config('seo.site_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/ai-logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('admin.css') }}?v={{ @filemtime(public_path('admin.css')) ?: time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="admin-body">

@auth
<aside class="admin-sidebar">
    <div class="admin-logo">
        <img src="{{ asset('img/aiGrowBot_black.png') }}" alt="AI Grow Bot">
    </div>
    <nav>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge"></i> Dashboard
        </a>
        @can('manage posts')
        <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
            <i class="fa-solid fa-newspaper"></i> Blog Posts
        </a>
        @endcan
        @can('manage categories')
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fa-solid fa-folder-tree"></i> Categories
        </a>
        @endcan
        @can('manage tags')
        <a href="{{ route('admin.tags.index') }}" class="{{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
            <i class="fa-solid fa-tags"></i> Tags
        </a>
        @endcan
        @can('manage seo')
        <a href="{{ route('admin.seo.index') }}" class="{{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
            <i class="fa-solid fa-magnifying-glass-chart"></i> SEO
        </a>
        @endcan
        @can('manage subscriptions')
        <a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <i class="fa-solid fa-credit-card"></i> Subscriptions
        </a>
        @endcan
        @can('manage users')
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i> Users
        </a>
        @endcan
        @can('view contact messages')
        <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
            <i class="fa-solid fa-inbox"></i> Messages
        </a>
        @endcan
        @can('manage settings')
        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i> Settings
        </a>
        @endcan
    </nav>
    <div class="admin-footer-side">
        <div class="admin-user">
            <strong>{{ auth()->user()->name }}</strong>
            <small>{{ auth()->user()->getRoleNames()->implode(', ') }}</small>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button></form>
    </div>
</aside>
@endauth

<main class="admin-main">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('admin-content')
</main>

<script>
// Attach CSRF to all fetch and delete forms
document.addEventListener('submit', function(e){
    const f = e.target;
    if (f.dataset.confirm && !confirm(f.dataset.confirm)) { e.preventDefault(); }
});
</script>
</body>
</html>
