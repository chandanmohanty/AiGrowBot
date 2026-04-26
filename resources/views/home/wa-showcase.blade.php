<!-- ============ WHATSAPP SHOWCASE ============ -->
<section class="section wa-showcase">
    <div class="wa-bg-shapes">
        <span></span><span></span><span></span>
    </div>
    <div class="container wa-wrap">
        <div class="wa-left reveal">
            <span class="pill-dark">
                <i class="fa-brands fa-whatsapp"></i> Now with WhatsApp Automation
            </span>
            <h2 class="wa-title">
                Automate Your <br/>
                Growth with <span class="gradient-text">AI</span>
            </h2>
            <p class="wa-desc">
                AI Grow Bot helps you scale faster with intelligent WhatsApp automation,
                smart chatbots, and real-time analytics &mdash; built for founders and teams
                who want real results.
            </p>
            <div class="wa-cta">
                <a href="https://aigrowbot.com/" class="btn btn-primary btn-glow">
                    Get Started Free <i class="fa-solid fa-arrow-right"></i>
                </a>
                @php $wa = \App\Models\Setting::get('whatsapp_number', '919870217934'); @endphp
                <a href="https://wa.me/{{ $wa }}?text=Hi%20AI%20Grow%20Bot%2C%20I%27d%20like%20to%20know%20more%20about%20your%20WhatsApp%20automation%20platform." target="_blank" rel="noopener" class="btn btn-whatsapp">
                    <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>
            <div class="wa-trust">
                <div class="avatars avatars-img">
                    @for ($i = 5; $i <= 15; $i++)
                        <span><img src="{{ asset("img/client{$i}.jpg") }}" alt="Client" loading="lazy" decoding="async"></span>
                    @endfor
                </div>
                <div class="stars-inline">
                    @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                    <span>Loved by <strong>10,000+</strong> teams worldwide</span>
                </div>
            </div>
        </div>

        <div class="wa-right reveal">
            <div class="phone-stage">
                <div class="phone">
                    <div class="phone-notch"></div>
                    <div class="phone-screen">
                        <div class="chat-header">
                            <div class="chat-avatar">
                                <img src="{{ asset('img/ai-logo.png') }}" alt="AI Grow Bot" class="logo-img">
                            </div>
                            <div class="chat-info">
                                <strong>AI Grow Bot</strong>
                                <small>online</small>
                            </div>
                            <div class="chat-actions">
                                <i class="fa-solid fa-video"></i>
                                <i class="fa-solid fa-phone"></i>
                            </div>
                        </div>
                        <div class="chat-body">
                            <div class="msg msg-in">Hi! I'm interested in your service<span class="msg-time">10:24</span></div>
                            <div class="msg msg-in">Can you help me grow my business?<span class="msg-time">10:24</span></div>
                            <div class="msg msg-out"><span class="ai-tag">AI Reply</span>Absolutely! I can automate your lead replies and qualify prospects 24/7 &#10024;<span class="msg-time">10:24 <i class="fa-solid fa-check-double"></i></span></div>
                            <div class="msg msg-out"><span class="ai-tag">AI Reply</span>Let me show you how we increased revenue by 4x for similar brands &#128200;<span class="msg-time">10:24 <i class="fa-solid fa-check-double"></i></span></div>
                            <div class="msg msg-in typing-msg"><span class="dots"><i></i><i></i><i></i></span></div>
                            <div class="msg msg-in">Amazing! Send me details please<span class="msg-time">10:25</span></div>
                        </div>
                        <div class="chat-footer">
                            <span>Type a message...</span>
                            <button><i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>

                <div class="stat-float stat-msg">
                    <div class="stat-float-icon green"><i class="fa-brands fa-whatsapp"></i></div>
                    <div><small>Messages sent</small><strong>24,892</strong></div>
                </div>
                <div class="stat-float stat-conv">
                    <div class="stat-float-icon orange"><i class="fa-solid fa-chart-line"></i></div>
                    <div><small>Conversion</small><strong>+34.2%</strong></div>
                </div>
                <div class="stat-float stat-ai">
                    <div class="stat-float-icon red"><i class="fa-solid fa-bolt"></i></div>
                    <div><small>AI replies/min</small><strong>142</strong></div>
                </div>

                <div class="wa-float-btn">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
            </div>
        </div>
    </div>
</section>
