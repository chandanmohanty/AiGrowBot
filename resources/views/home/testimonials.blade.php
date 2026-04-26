<!-- ============ TESTIMONIALS ============ -->
<section class="section testimonials" id="testimonials">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Testimonials</span>
            <h2>Loved by <span class="gradient-text">growing teams</span></h2>
            <p>Real stories from businesses scaling with AI Grow Bot.</p>
        </div>
    </div>

    @php
        $reviews = [
            ['PS', '#22c55e', 'Priya Sharma', '2 months ago', 'AI Grow Bot has completely transformed how we manage WhatsApp enquiries. The AI handles 80% of our customer questions automatically, saving us hours every day.'],
            ['RG', '#f97316', 'Rahul Gupta',  '3 months ago', 'Best WhatsApp automation platform we have used. Drip messaging alone doubled our lead conversions within the first month. Highly recommended.'],
            ['AS', '#14b8a6', 'Avantika Singh','10 months ago','Setup was incredibly easy. Our support team is much more efficient now thanks to automatic agent routing. The dashboard is clean and easy to use.'],
            ['SK', '#10b981', 'Sanjay Kumar', '5 months ago', 'Real-time analytics gives us insights we never had before. We can now track every campaign and adjust instantly. A must-have for any serious business.'],
            ['NP', '#a855f7', 'Neha Patel',   '4 months ago', 'Shopify integration was seamless. Abandoned cart messages helped us recover so much lost revenue. The team is also very responsive whenever we need help.'],
            ['AM', '#ec4899', 'Arjun Mehta',  '7 months ago', 'Customer support is outstanding. The team helped us set up everything in under an hour. Our WhatsApp marketing has never been this organised.'],
            ['KR', '#ef4444', 'Kavya Reddy',  '1 month ago',  'Engagement rates went up by 40% in the first month. AI Grow Bot is a game changer for any business that depends on customer conversations.'],
        ];

        $googleSvg = '<svg viewBox="0 0 24 24" width="22" height="22"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>';
    @endphp

    <div class="testi-marquee">
        <div class="testi-track">
            @foreach ($reviews as [$initials, $color, $name, $ago, $text])
                <article class="google-card">
                    <div class="gc-head">
                        <div class="gc-user">
                            <div class="gc-avatar" style="background:{{ $color }}">{{ $initials }}</div>
                            <div>
                                <strong>{{ $name }}</strong>
                                <small>{{ $ago }}</small>
                            </div>
                        </div>
                        <span class="gc-google" aria-label="Google review">{!! $googleSvg !!}</span>
                    </div>
                    <div class="gc-stars">
                        @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                        <span class="gc-verified" title="Verified"><i class="fa-solid fa-circle-check"></i></span>
                    </div>
                    <p class="gc-text">{{ $text }}</p>
                </article>
            @endforeach

            {{-- Duplicate for seamless loop --}}
            @foreach ($reviews as [$initials, $color, $name, $ago, $text])
                <article class="google-card" aria-hidden="true">
                    <div class="gc-head">
                        <div class="gc-user">
                            <div class="gc-avatar" style="background:{{ $color }}">{{ $initials }}</div>
                            <div><strong>{{ $name }}</strong><small>{{ $ago }}</small></div>
                        </div>
                        <span class="gc-google">{!! $googleSvg !!}</span>
                    </div>
                    <div class="gc-stars">
                        @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                        <span class="gc-verified"><i class="fa-solid fa-circle-check"></i></span>
                    </div>
                    <p class="gc-text">{{ $text }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
