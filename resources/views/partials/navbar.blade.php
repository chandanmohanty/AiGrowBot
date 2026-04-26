<!-- ============ NAVBAR ============ -->
@php
    $contactEmail   = \App\Models\Setting::get('contact_email', 'support@aigrowbot.com');
    $contactPhone   = \App\Models\Setting::get('contact_phone', '+91 80760 96255');
    $socialFacebook = \App\Models\Setting::get('social_facebook', '#');
    $socialYoutube  = \App\Models\Setting::get('social_youtube',  '#');
    $socialLinkedin = \App\Models\Setting::get('social_linkedin', '#');
    $socialInsta    = \App\Models\Setting::get('social_instagram','#');
@endphp

<header class="navbar" id="navbar">
    <!-- Top contact bar -->
    <div class="topbar">
        <div class="container topbar-inner">
            <div class="topbar-left">
                <a href="mailto:{{ $contactEmail }}" class="topbar-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>{{ $contactEmail }}</span>
                </a>
                <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}" class="topbar-item">
                    <i class="fa-solid fa-phone"></i>
                    <span>{{ $contactPhone }}</span>
                </a>
            </div>
            <div class="topbar-socials">
                <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="{{ $socialYoutube }}"  target="_blank" rel="noopener" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                <a href="{{ $socialLinkedin }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="{{ $socialInsta }}"    target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </div>

    <div class="container nav-wrap">
        <a href="{{ url('/') }}" class="logo logo-full" aria-label="AI Grow Bot Home">
            <img src="{{ asset('img/aiGrowBot_black.png') }}" alt="AI Grow Bot" class="logo-full-img">
        </a>

        <nav class="nav-links" id="navLinks">
            <a href="{{ url('/#features') }}">Features</a>
            <a href="{{ url('/#how') }}">How it Works</a>
            <a href="{{ url('/#pricing') }}">Pricing</a>
            <a href="{{ url('/#testimonials') }}">Reviews</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            <a href="{{ url('/#faq') }}">FAQ</a>
            <a href="{{ url('/#contact') }}">Contact</a>
        </nav>

        <div class="nav-cta">
            <a href="https://app.aigrowbot.com/register" class="btn btn-primary btn-glow">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Login
            </a>
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</header>
