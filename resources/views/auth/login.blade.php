@extends('layouts.admin')

@section('admin-content')
<div class="login-stage">
    <span class="login-blob blob-1" aria-hidden="true"></span>
    <span class="login-blob blob-2" aria-hidden="true"></span>
    <span class="login-blob blob-3" aria-hidden="true"></span>

    <div class="login-shell">
        <!-- Brand / hero panel -->
        <aside class="login-hero">
            <div class="login-hero-inner">
                <div class="login-hero-top">
                    <span class="login-hero-chip"><i class="fa-solid fa-shield-halved"></i> Admin Console</span>
                    <h1>Welcome Back</h1>
                    <p>Sign in to manage posts, users, SEO and settings for your AI Grow Bot site.</p>
                </div>

                <div class="login-hero-logo">
                    <img src="{{ asset('img/aiGrowBot_black.png') }}" alt="AI Grow Bot">
                </div>

                <ul class="login-hero-list">
                    <li><i class="fa-solid fa-circle-check"></i> Full blog CRUD with rich text &amp; SEO</li>
                    <li><i class="fa-solid fa-circle-check"></i> Role-based access for your team</li>
                    <li><i class="fa-solid fa-circle-check"></i> Secure, rate-limited sign-in</li>
                </ul>

                <div class="login-hero-foot">
                    <div class="login-hero-badge">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <strong>Effortless WhatsApp Marketing</strong>
                        <small>Engage &amp; convert — all from one place</small>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Form panel -->
        <section class="login-panel">
            <a href="{{ url('/') }}" class="login-back" title="Back to site">
                <i class="fa-solid fa-arrow-left"></i> Back to site
            </a>

            <div class="login-panel-inner">
                <div class="login-panel-head">
                    <div class="login-panel-icon">
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </div>
                    <h2>Log in</h2>
                    <p>Please enter your credentials to continue</p>
                </div>

                @if ($errors->any())
                    <div class="login-alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div>
                            @foreach ($errors->all() as $e)
                                <div>{{ $e }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="login-form" novalidate>
                    @csrf

                    <div class="login-field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="login-email"><span class="req">*</span> Email</label>
                        <div class="login-input">
                            <i class="fa-solid fa-envelope"></i>
                            <input id="login-email" type="email" name="email"
                                   placeholder="you@example.com"
                                   required autofocus autocomplete="email"
                                   value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="login-field {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="login-password"><span class="req">*</span> Password</label>
                        <div class="login-input">
                            <i class="fa-solid fa-lock"></i>
                            <input id="login-password" type="password" name="password"
                                   placeholder="Enter your password"
                                   required autocomplete="current-password">
                            <button type="button" class="login-eye" aria-label="Toggle password visibility"
                                    data-target="login-password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="login-row">
                        <label class="login-check">
                            <input type="checkbox" name="remember">
                            <span class="login-check-box"><i class="fa-solid fa-check"></i></span>
                            Remember me
                        </label>
                        <a href="#" class="login-forgot">Forgot Password?</a>
                    </div>

                    <button type="submit" class="login-submit">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Log in</span>
                    </button>

                    <p class="login-foot-note">
                        Don't have an account?
                        <a href="#">Contact your administrator</a>
                    </p>

                    @if (app()->environment('local'))
                        <div class="login-hint">
                            <strong><i class="fa-solid fa-circle-info"></i> Default admin</strong>
                            <code>admin@aigrowbot.local</code> / <code>ChangeMe!123</code>
                        </div>
                    @endif
                </form>
            </div>
        </section>
    </div>
</div>

<script>
(function () {
    document.querySelectorAll('.login-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            if (!input) return;
            var isPwd = input.type === 'password';
            input.type = isPwd ? 'text' : 'password';
            var icon = btn.querySelector('i');
            if (icon) icon.className = isPwd ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
        });
    });
})();
</script>
@endsection
