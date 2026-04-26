<!-- ============ HERO ============ -->
<section class="hero">
    <div class="floating-shapes">
        <span class="shape shape-1"></span>
        <span class="shape shape-2"></span>
        <span class="shape shape-3"></span>
        <span class="shape shape-4"></span>
    </div>

    <div class="container hero-wrap">
        <div class="hero-left reveal">
            <div class="hero-badge meta-partner">
                <img src="{{ asset('img/meta-business-partner.png') }}"
                     alt="Meta Business Partner"
                     loading="lazy"
                     decoding="async">
            </div>
            <h1 class="hero-title">
                Grow <span class="gradient-text">Faster</span><br />
                with AI <span class="underline-accent">Automation</span>
            </h1>
            <p class="hero-sub">
                AI Grow Bot helps you automate tasks, track growth, and scale
                smarter &mdash; all from one clean, intelligent dashboard.
            </p>

            <div class="hero-cta">
                <a href="https://aigrowbot.com/" class="btn btn-primary btn-glow">
                    Get Started <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="#pricing" class="btn btn-ghost">
                    <i class="fa-solid fa-play"></i> View Demo
                </a>
            </div>

            <div class="hero-meta">
                <div class="avatars avatars-img">
                    @for ($i = 5; $i <= 15; $i++)
                        <span><img src="{{ asset("img/client{$i}.jpg") }}" alt="Client" loading="lazy" decoding="async"></span>
                    @endfor
                </div>
                <div class="meta-text">
                    <div class="meta-stars">
                        @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                    </div>
                    <span><strong>10,000+</strong> businesses already growing</span>
                </div>
            </div>
        </div>

        <div class="hero-right reveal">
            <div class="hero-mock glass">
                <div class="mock-topbar">
                    <span></span><span></span><span></span>
                    <div class="mock-url">app.aigrowbot.com</div>
                </div>
                <div class="mock-body">
                    <div class="mock-side">
                        <div class="mock-logo">
                            <img src="{{ asset('img/aiGrowBot_black.png') }}" alt="AI Grow Bot" class="mock-logo-img">
                        </div>
                        <ul>
                            <li class="active"><i class="fa-solid fa-chart-line"></i> Dashboard</li>
                            <li><i class="fa-solid fa-message"></i> Templates</li>
                            <li><i class="fa-solid fa-bullhorn"></i> Campaigns</li>
                            <li><i class="fa-solid fa-users"></i> Contacts</li>
                            <li><i class="fa-solid fa-gear"></i> Settings</li>
                        </ul>
                    </div>
                    <div class="mock-main">
                        <div class="mock-stats">
                            <div class="stat-card">
                                <span class="stat-label">Contacts</span>
                                <span class="stat-value">12,480</span>
                                <span class="stat-delta up"><i class="fa-solid fa-arrow-up"></i> 24%</span>
                            </div>
                            <div class="stat-card">
                                <span class="stat-label">Campaigns</span>
                                <span class="stat-value">348</span>
                                <span class="stat-delta up"><i class="fa-solid fa-arrow-up"></i> 12%</span>
                            </div>
                            <div class="stat-card">
                                <span class="stat-label">Revenue</span>
                                <span class="stat-value">$84.2k</span>
                                <span class="stat-delta up"><i class="fa-solid fa-arrow-up"></i> 31%</span>
                            </div>
                        </div>
                        <div class="mock-chart">
                            <div class="chart-bars">
                                <span style="--h:40%"></span>
                                <span style="--h:65%"></span>
                                <span style="--h:50%"></span>
                                <span style="--h:80%"></span>
                                <span style="--h:60%"></span>
                                <span style="--h:90%"></span>
                                <span style="--h:72%"></span>
                                <span style="--h:95%"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="float-card float-wa">
                <div class="wa-icon"><i class="fa-brands fa-whatsapp"></i></div>
                <div class="wa-text">
                    <strong>WhatsApp Bot</strong>
                    <span class="typing"><i></i><i></i><i></i></span>
                </div>
            </div>

            <div class="float-card float-ai">
                <div class="ai-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <div>
                    <strong>+328 leads</strong>
                    <small>Automated today</small>
                </div>
            </div>
        </div>
    </div>
</section>
