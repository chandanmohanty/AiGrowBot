<!-- ============ PRICING ============ -->
@php
    // Fallback: if controller didn't pass plans (e.g. page embedded elsewhere),
    // fetch active plans directly.
    if (! isset($plans)) {
        $plans = \App\Models\SubscriptionPlan::active()->ordered()->get();
    }
    $gridClass = 'grid-' . min(4, max(1, $plans->count()));
@endphp
@if ($plans->count())
<section class="section pricing" id="pricing">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">Pricing</span>
            <h2>Simple, <span class="gradient-text">Flexible Pricing</span></h2>
            <p>Choose the plan that fits your growth &mdash; upgrade, downgrade, or cancel any time.</p>

            <div class="pricing-toggles">
                <div class="billing-toggle">
                    <span class="billing-label" id="labelMonthly">Billed monthly</span>
                    <button class="toggle-switch" id="billingToggle" aria-label="Toggle billing period">
                        <span class="toggle-knob"></span>
                    </button>
                    <span class="billing-label" id="labelYearly">Billed yearly</span>
                </div>

                <div class="billing-toggle currency-toggle">
                    <span class="billing-label" id="labelINR">INR <small>&#8377;</small></span>
                    <button class="toggle-switch" id="currencyToggle" aria-label="Toggle currency">
                        <span class="toggle-knob"></span>
                    </button>
                    <span class="billing-label" id="labelUSD">USD <small>$</small></span>
                </div>
            </div>
            @php
                $maxSave = $plans->max('save_percent');
            @endphp
            @if ($maxSave)
                <p class="save-note"><i class="fa-solid fa-gift"></i> Save up to <strong>{{ $maxSave }}%</strong> with yearly billing</p>
            @endif
            <p class="fx-note" id="fxNote" hidden></p>
        </div>

        <div class="grid {{ $gridClass }} pricing-grid">
            @foreach ($plans as $plan)
                @php
                    $monthlyInt   = (int) round((float) $plan->price_monthly);
                    $yearlyInt    = (int) round((float) $plan->price_yearly);
                    $currencyHtml = $plan->currency === 'USD' ? '$' : '&#8377;';
                    $cardClasses  = 'price-card reveal';
                    if ($plan->is_popular)    $cardClasses .= ' popular';
                    if ($plan->is_free_trial) $cardClasses .= ' price-free';
                    $btnClass = 'btn btn-price btn-glow' . ($plan->is_popular ? ' btn-price-popular' : '');
                @endphp
                <div class="{{ $cardClasses }}">
                    @if ($plan->is_free_trial && $plan->tagline)
                        <div class="free-tag"><i class="fa-solid fa-gift"></i> {{ $plan->tagline }}</div>
                    @elseif ($plan->is_popular && $plan->tagline)
                        <div class="popular-tag"><i class="fa-solid fa-crown"></i> {{ $plan->tagline }}</div>
                    @endif

                    <div class="price-top">
                        <h3>{{ $plan->name }}</h3>
                        @if ($plan->save_percent && ! $plan->is_free_trial)
                            <span class="save-badge" data-yearly-only>Save {{ $plan->save_percent }}%</span>
                        @endif
                    </div>

                    <div class="price-value">
                        @if ($plan->is_free_trial)
                            <span class="amount amount-free" data-free>{!! $currencyHtml !!}{{ $monthlyInt }}</span>
                            <span class="period">/ {{ $plan->free_trial_days ?? 7 }} Days</span>
                        @else
                            <span class="currency">{!! $currencyHtml !!}</span>
                            <span class="amount"
                                  data-monthly="{{ $monthlyInt }}"
                                  data-yearly="{{ $yearlyInt }}">{{ number_format($monthlyInt) }}</span>
                            <span class="period">/ <span data-period-label>Month</span></span>
                        @endif
                    </div>

                    @if ($plan->description)
                        <p class="price-desc">{{ $plan->description }}</p>
                    @endif

                    <a href="{{ $plan->cta_url ?: 'https://app.aigrowbot.com/register' }}" class="{{ $btnClass }}">
                        <span>{{ $plan->cta_label }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    @if (! empty($plan->features))
                        <ul class="price-features">
                            @foreach ($plan->features as $f)
                                <li class="{{ !empty($f['included']) ? 'yes' : 'no' }}">
                                    <i class="fa-solid {{ !empty($f['included']) ? 'fa-check' : 'fa-xmark' }}"></i>
                                    {{ $f['text'] }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
