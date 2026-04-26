<!-- ============ FOOTER ============ -->
<footer class="footer">
    <div class="container footer-wrap">
        <div class="foot-brand">
            <a href="{{ url('/') }}" class="logo logo-full">
                <img src="{{ asset('img/aiGrowBot_black.png') }}" alt="AI Grow Bot" class="logo-full-img logo-full-img-dark">
            </a>
            <p>Empower your business with AI-driven automation, smart insights, and scalable growth.</p>
            <div class="socials">
                <a href="{{ \App\Models\Setting::get('social_twitter', '#') }}" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="{{ \App\Models\Setting::get('social_linkedin', '#') }}" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="{{ \App\Models\Setting::get('social_facebook', '#') }}" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="{{ \App\Models\Setting::get('social_instagram', '#') }}" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>

        <div class="foot-cols">
            <div class="foot-col">
                <h4>Product</h4>
                <a href="{{ url('/#features') }}">Features</a>
                <a href="{{ url('/#how') }}">How it Works</a>
                <a href="{{ url('/#benefits') }}">Benefits</a>
            </div>
            <div class="foot-col">
                <h4>Company</h4>
                <a href="{{ url('/#') }}">About</a>
                <a href="{{ url('/#contact') }}">Contact</a>
                <a href="{{ route('blog.index') }}">Blog</a>
            </div>
            <div class="foot-col">
                <h4>Legal</h4>
                <a href="{{ url('/privacy') }}">Privacy</a>
                <a href="{{ url('/terms') }}">Terms</a>
                <a href="{{ url('/security') }}">Security</a>
            </div>
        </div>
    </div>

    <div class="container foot-payments">
        <div class="pay-label"><i class="fa-solid fa-lock"></i> Secure Payments</div>
        <div class="pay-logos">
            <div class="pay-item pay-item-razorpay" title="Razorpay">
                <img src="{{ asset('img/razorpay.svg') }}" alt="Razorpay">
            </div>
            <div class="pay-item" title="UPI">
                <svg viewBox="0 0 90 32" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="8,4 20,4 14,28" fill="#088F3E"/>
                    <polygon points="18,4 30,4 24,28" fill="#EE7623"/>
                    <text x="36" y="17" font-family="Inter, Arial, sans-serif" font-weight="900" font-size="11" fill="#088F3E">UPI</text>
                    <text x="36" y="27" font-family="Inter, Arial, sans-serif" font-weight="600" font-size="7" fill="#aaa">PAYMENTS</text>
                </svg>
            </div>
            <div class="pay-item" title="Mastercard">
                <svg viewBox="0 0 90 32" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="28" cy="16" r="11" fill="#EB001B"/>
                    <circle cx="42" cy="16" r="11" fill="#F79E1B"/>
                    <text x="58" y="20" font-family="Inter, Arial, sans-serif" font-weight="700" font-size="9" fill="#fff">Mastercard</text>
                </svg>
            </div>
            <div class="pay-item" title="Visa">
                <svg viewBox="0 0 90 32" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="6" width="82" height="20" rx="3" fill="#1A1F71"/>
                    <text x="45" y="21" font-family="Arial, sans-serif" font-style="italic" font-weight="900" font-size="14" fill="#fff" text-anchor="middle" letter-spacing="1">VISA</text>
                </svg>
            </div>
            <div class="pay-item" title="Google Pay">
                <svg viewBox="0 0 90 32" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="14" cy="16" r="9" fill="none" stroke="#fff" stroke-width="1.5"/>
                    <text x="9" y="20" font-family="Inter, Arial, sans-serif" font-weight="700" font-size="11" fill="#fff">G</text>
                    <text x="28" y="14" font-family="Inter, Arial, sans-serif" font-weight="600" font-size="9" fill="#9aa0a6">Google</text>
                    <text x="28" y="26" font-family="Inter, Arial, sans-serif" font-weight="700" font-size="11" fill="#fff">Pay</text>
                </svg>
            </div>
            <div class="pay-item" title="Paytm">
                <svg viewBox="0 0 90 32" xmlns="http://www.w3.org/2000/svg">
                    <text x="6" y="22" font-family="Inter, Arial, sans-serif" font-weight="900" font-size="16" fill="#00BAF2">Pay</text>
                    <text x="38" y="22" font-family="Inter, Arial, sans-serif" font-weight="900" font-size="16" fill="#fff">tm</text>
                </svg>
            </div>
            <div class="pay-item" title="RuPay">
                <svg viewBox="0 0 90 32" xmlns="http://www.w3.org/2000/svg">
                    <text x="8" y="22" font-family="Inter, Arial, sans-serif" font-weight="900" font-size="14" fill="#0F7B3A">Ru</text>
                    <text x="34" y="22" font-family="Inter, Arial, sans-serif" font-weight="900" font-size="14" fill="#EC1C24">Pay</text>
                </svg>
            </div>
        </div>
    </div>

    <div class="container foot-bottom">
        <span>&copy; <span id="year">{{ date('Y') }}</span> {{ \App\Models\Setting::get('site_name', 'AI Grow Bot') }}. All rights reserved.</span>
        <span class="foot-legal">
            <a href="{{ url('/privacy') }}">Privacy</a> &middot;
            <a href="{{ url('/terms') }}">Terms</a> &middot;
            <a href="{{ url('/refund') }}">Refund Policy</a>
        </span>
    </div>
</footer>
