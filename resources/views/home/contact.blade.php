<!-- ============ CONTACT US ============ -->
<section class="section contact-section" id="contact">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Contact Us</span>
            <h2>Let's <span class="gradient-text">talk growth</span></h2>
            <p>Have questions? We're here to help. Reach out and we'll reply within minutes on WhatsApp.</p>
        </div>

        <div class="contact-wrap">
            <div class="contact-info reveal">
                <h3>Get in touch</h3>
                <p class="info-lead">Whether you're a startup or enterprise, our team is ready to help you scale smarter with AI.</p>

                @php
                    $email = \App\Models\Setting::get('contact_email', 'support@aigrowbot.com');
                    $phone = \App\Models\Setting::get('contact_phone', '+91 80760 96255');
                    $wa    = \App\Models\Setting::get('whatsapp_number', '919870217934');
                @endphp

                <a href="mailto:{{ $email }}" class="info-item">
                    <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div><small>Email us</small><strong>{{ $email }}</strong></div>
                </a>

                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="info-item">
                    <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                    <div><small>Call us</small><strong>{{ $phone }}</strong></div>
                </a>

                <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="info-item wa">
                    <div class="info-icon wa-bg"><i class="fa-brands fa-whatsapp"></i></div>
                    <div><small>Chat on WhatsApp</small><strong>+{{ substr_replace($wa, ' ', 2, 0) }}</strong></div>
                </a>

                <div class="info-social">
                    <span>Follow us</span>
                    <div>
                        <a href="{{ \App\Models\Setting::get('social_twitter', '#') }}" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="{{ \App\Models\Setting::get('social_linkedin', '#') }}" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="{{ \App\Models\Setting::get('social_facebook', '#') }}" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="{{ \App\Models\Setting::get('social_instagram', '#') }}" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            <form class="contact-form reveal" id="enquiryForm" action="{{ route('contact.store') }}" method="POST" novalidate>
                @csrf
                <h3>Send an enquiry</h3>
                <p class="form-lead"><i class="fa-brands fa-whatsapp" style="color:#25d366"></i> Submissions are sent instantly via WhatsApp</p>

                <div class="form-row">
                    <div class="form-field">
                        <label for="fName">Full Name *</label>
                        <input type="text" id="fName" name="name" required placeholder="John Doe" value="{{ old('name') }}" autocomplete="name" />
                    </div>
                    <div class="form-field">
                        <label for="fEmail">Email *</label>
                        <input type="email" id="fEmail" name="email" required placeholder="you@company.com" value="{{ old('email') }}" autocomplete="email" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label for="fPhone">Phone</label>
                        <input type="tel" id="fPhone" name="phone" placeholder="+91 98xxxxxxxx" value="{{ old('phone') }}" autocomplete="tel" />
                    </div>
                    <div class="form-field">
                        <label for="fSubject">Subject *</label>
                        <select id="fSubject" name="subject" required>
                            <option value="">Select a topic</option>
                            @foreach (['General Enquiry','Pricing & Plans','Demo Request','Technical Support','Partnership'] as $opt)
                                <option value="{{ $opt }}" @selected(old('subject') === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-field">
                    <label for="fMessage">Message *</label>
                    <textarea id="fMessage" name="message" required rows="4" placeholder="Tell us about your business and how we can help...">{{ old('message') }}</textarea>
                </div>

                {{-- Honeypot --}}
                <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;height:0;width:0;opacity:0" />

                <button type="submit" class="btn btn-primary btn-glow form-submit">
                    <i class="fa-brands fa-whatsapp"></i> Send via WhatsApp
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

                <p class="form-note">By submitting, you'll be redirected to WhatsApp with your message pre-filled.</p>
            </form>
        </div>
    </div>
</section>
