<!-- ============ FAQ ============ -->
@php
    $faqs = [
        ['Is WhatsApp Marketing legal?',
         'Yes, WhatsApp Marketing is completely legal when carried out through the <strong>Official WhatsApp Business API</strong>. To avoid account bans or privacy risks, refrain from using unofficial tools.'],
        ['Is there any fee to procure the WhatsApp Business API through AiGrowBot?',
         'No, there is no procurement fee. AiGrowBot helps businesses get the official WhatsApp Business API <strong>completely free of cost</strong>, with no hidden charges.'],
        ['Is WhatsApp Marketing effective?',
         'Yes, absolutely! WhatsApp Marketing is one of the most effective channels for customer engagement. Messages sent via WhatsApp have an <strong>open rate of up to 98%</strong> and a <strong>click-through rate of 45–60%</strong>, giving brands exceptional visibility to boost sales and revenue.'],
        ['Are marketing messages free?',
         'No. Marketing messages such as promotions, offers, and product updates are chargeable. Each marketing message delivered costs <strong>&#8377;0.88 per message</strong> (for Indian users).'],
    ];
@endphp

<section class="section faq-section" id="faq">
    <div class="faq-bg"><span></span><span></span></div>
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><i class="fa-regular fa-circle-question"></i> Questions &amp; Answers</span>
            <h2>Your questions about <span class="gradient-text">WhatsApp marketing</span>, answered</h2>
            <p>Everything you need to know before getting started with AI Grow Bot.</p>
        </div>

        <div class="faq-wrap reveal">
            <div class="faq-category">
                <span class="faq-chip"><i class="fa-solid fa-rocket"></i> Getting Started</span>
            </div>

            <div class="faq-list">
                @foreach ($faqs as $idx => [$q, $a])
                    <div class="faq-item">
                        <button class="faq-q" aria-expanded="false">
                            <span class="faq-num">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="faq-qtext">{{ $q }}</span>
                            <span class="faq-toggle"><i class="fa-solid fa-plus"></i></span>
                        </button>
                        <div class="faq-a">
                            <div class="faq-a-inner"><p>{!! $a !!}</p></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="faq-cta">
                <div>
                    <strong>Still have questions?</strong>
                    <small>Our team is ready to help you 24/7.</small>
                </div>
                <a href="#contact" class="btn btn-primary btn-glow">
                    Contact Us <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
        '@type' => 'Question',
        'name'  => $f[0],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f[1])],
    ], $faqs),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
