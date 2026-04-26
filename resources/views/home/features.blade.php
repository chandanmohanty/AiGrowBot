<!-- ============ FEATURES ============ -->
<section class="section features" id="features">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">More Features</span>
            <h2>Smarter WhatsApp Marketing, <span class="gradient-text">Made Simple</span></h2>
            <p>Turn WhatsApp into your growth engine with AI replies, automation, and insights.</p>
        </div>

        @php
            $features = [
                ['fa-solid fa-mobile-screen',      'Number Coexistence',                'Use the same WhatsApp number in multiple places including phone and WhatChimp.'],
                ['fa-solid fa-robot',              'AI Agent',                          'Train AI on top of your business using Website, PDF, FAQs to automate enquiries.'],
                ['fa-solid fa-tower-broadcast',    'High Speed Broadcasting',           'Get the highest broadcasting speed supported by the official WhatsApp Business API.'],
                ['fa-solid fa-chart-column',       'Powerful Analytics',                'Access up-to-the-minute insights, enabling you to make timely and informed decisions.'],
                ['fa-solid fa-droplet',            'Drip Messaging',                    'Nurture your leads with a series of automated messages in certain time intervals.'],
                ['fa-solid fa-user-check',         'Interactive User Input Collection', 'Automate collecting customer details step by step, asking one question at a time.'],
                ['fa-solid fa-pen-to-square',      'Native WhatsApp Form',              'Collect information directly inside WhatsApp without sending to external website.'],
                ['fa-solid fa-headset',            'Automatic Agent Routing',           'Automatically route chats to the right agent based on department or rules you set.'],
                ['fa-solid fa-user-secret',        'Phone Number Masking',              'Hide the WhatsApp number of incoming chats from your support agents.'],
                ['fa-solid fa-keyboard',           'Custom Fields',                     "Create custom fields to store more than just 'name' and 'number'."],
                ['fa-solid fa-chart-pie',          'Segment Subscribers',               'Sending messages to targeted segments can increase engagement by more than 25%.'],
                ['fa-solid fa-code',               'Powerful API',                      "Use API endpoints to send messages and manage your subscriber's list."],
                ['fa-brands fa-wordpress',         'WooCommerce',                       'Connect with WooCommerce & send messages for Abandoned Cart, Order Confirmation, & more.'],
                ['fa-brands fa-shopify',           'Shopify',                           'Connect with Shopify & send messages for Abandoned Cart, Order Confirmation, & more.'],
                ['fa-solid fa-plug-circle-bolt',   'Custom Webhook Listener',           'Capture JSON data from any external app or system and trigger a WhatsApp message.'],
            ];
        @endphp

        <div class="grid grid-3">
            @foreach ($features as [$icon, $title, $desc])
                <div class="feature-card reveal">
                    <div class="feat-icon"><i class="{{ $icon }}"></i></div>
                    <h3>{{ $title }}</h3>
                    <p>{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
