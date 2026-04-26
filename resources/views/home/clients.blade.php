<!-- ============ CLIENT LOGOS SLIDER ============ -->
<section class="clients-slider">
    <div class="container">
        <p class="clients-title">Our happy <span class="gradient-text">clients</span></p>
    </div>
    <div class="marquee">
        <div class="marquee-track">
            @for ($i = 5; $i <= 18; $i++)
                <div class="client-logo"><img src="{{ asset("img/client{$i}.jpg") }}" alt="Client {{ $i }}" loading="lazy" decoding="async"></div>
            @endfor
        </div>
    </div>
</section>
