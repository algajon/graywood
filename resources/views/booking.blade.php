@extends('layouts.app')

@section('title', 'Book a Call - Agent Bookr')

@section('content')
<section class="py-14 booking-hero min-h-screen relative">
  {{-- Hide global chrome on this page + page-specific variables --}}
  <style>
    header#main-header, #phone-bar, footer { display: none !important; }

    :root{
      --accent: #16a34a;           /* emerald-600 */
      --accent-ink: #052e16;       /* deep brand green */
    }

    /* Background polish */
    .booking-hero{
      --bg1:#083226; --bg2:#05321b; --bg3:#052e16;
      background:
        radial-gradient(1200px 600px at 10% 0%, rgba(22,163,74,.14), transparent 60%),
        radial-gradient(1000px 500px at 90% 20%, rgba(6,95,70,.16), transparent 60%),
        linear-gradient(180deg,var(--bg1) 0%, var(--bg2) 45%, var(--bg3) 100%);
      background-attachment: fixed;
    }

    /* Card */
    .booking-card{
      border-radius: 18px;
      box-shadow:
        0 30px 60px rgba(3, 7, 12, .40),
        0 12px 24px rgba(3, 7, 12, .24);
      overflow: hidden;
      border: 1px solid rgba(255,255,255,.06);
      backdrop-filter: saturate(120%);
    }

    /* Decorative frame behind the iframe to make the widget feel native */
    .iframe-frame{
      background: linear-gradient(180deg, rgba(255,255,255,.85), rgba(255,255,255,.96));
      border-radius: 14px;
      box-shadow:
        0 8px 24px rgba(2, 6, 4, .20),
        inset 0 0 0 1px rgba(2, 6, 4, .06);
    }

    /* Calendar height — tighter to prevent bottom gap */
    /* Between 900px and 1200px, prefer 100vh so it fills the screen but never gets oversized */
#calendar-shell{
  height: clamp(960px, calc(95vh), 1180px);
}

    /* Loader overlay */
    #booking-loader{ backdrop-filter: blur(6px) saturate(120%); }
    #booking-loader .spinner{
      width: 68px; height: 68px; border-radius: 9999px;
      border: 4px solid rgba(255,255,255,.18);
      border-top-color: #fff; animation: spin 1s linear infinite;
    }
    @keyframes spin{ to{ transform: rotate(360deg);} }

    /* Back button */
    #booking-back-btn{
      box-shadow: 0 12px 28px rgba(2,6,4,.42);
      border: 1px solid rgba(255,255,255,.28);
      backdrop-filter: blur(8px);
    }
    #booking-back-btn:hover{ transform: translateY(1px); }
    #booking-back-btn:focus-visible{ outline: 3px solid var(--accent); outline-offset: 2px; }

    @media (prefers-reduced-motion: reduce){
      #booking-back-btn, .booking-card, .spinner{ animation: none !important; transition: none !important; }
    }
  </style>

  <!-- Fixed Back button -->
  <button id="booking-back-btn"
          class="fixed left-4 top-4 z-50 inline-flex items-center gap-2 bg-white/90 text-[color:var(--accent-ink)]
                 px-4 py-2 rounded-full hover:opacity-95 transition"
          aria-label="Back">
    <svg class="w-4 h-4 opacity-95" viewBox="0 0 24 24" fill="none" aria-hidden="true">
      <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span class="font-semibold">Back</span>
  </button>

  <div class="container mx-auto px-4 max-w-6xl">
    <div class="text-center mb-8 md:mb-10 pt-2">
      <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-3">
        Schedule Your Strategy Call
      </h1>
      <p class="text-base md:text-lg text-gray-200/90 max-w-2xl mx-auto">
        Pick a time that works for you. We’ll walk you through the system and next steps.
      </p>
    </div>

    <div class="mx-auto booking-card bg-white/5 max-w-5xl relative">
      <div class="h-1.5 w-full"
           style="background: linear-gradient(90deg, var(--accent), #22c55e 40%, #86efac 100%);"></div>

      <!-- Loader -->
      <div id="booking-loader"
           class="absolute inset-0 flex items-center justify-center bg-black/55 z-50"
           aria-live="polite" aria-busy="true">
        <div class="text-center px-6">
          <div class="spinner mx-auto mb-4"></div>
          <div class="text-white text-lg font-semibold">Loading calendar…</div>
          <div class="text-gray-200/80 text-sm mt-2">
            If it takes more than a few seconds, try disabling ad-blockers or open in a new tab below.
          </div>
        </div>
      </div>

      <div class="p-4 sm:p-6 md:p-8">
        <div class="iframe-frame p-1.5 md:p-2 rounded-xl">
          <div id="calendar-shell" class="relative rounded-lg overflow-hidden">
            <iframe id="booking-iframe"
                    src="{{ $src }}"
                    title="Booking calendar"
                    style="position:absolute;inset:0;border:0;width:100%;height:100%;"
                    scrolling="auto" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>

  <!-- Action row -->
        <div class="mt-4 flex items-center justify-between gap-3 text-[color:var(--accent-ink)]">
          <div class="hidden md:flex items-center gap-2 text-white/80">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
              <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-sm">Typical call: 15–30 minutes</span>
          </div>

          <div class="flex items-center gap-3">
            {{-- Optional fallback button (kept commented). --}}
            {{--
            <a id="booking-fallback-link"
               href="https://api.leadconnectorhq.com/widget/booking/ToUBYQOsru57Q83FhIc0"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white shadow hover:opacity-95 transition"
               style="background: var(--accent);">
              Open calendar in new tab
            </a>
            --}}
          </div>
        </div>
      </div>
    </div>

    <p class="text-center text-sm text-white/70 mt-6">
      Having trouble? <a href="mailto:hello@agentbookr.com" class="underline decoration-white/50 hover:decoration-white">Email support</a>.
    </p>

    {{-- Debug: show computed embed URL in a small collapsible panel --}}
    <div class="fixed right-4 bottom-4 z-50 text-xs">
      <details class="text-gray-200/80 bg-black/30 p-2 rounded backdrop-blur">
        <summary class="cursor-pointer">Embed URL (debug)</summary>
        <div class="break-all max-w-xs mt-2">{{ $src }}</div>
      </details>
    </div>
  </div>
</section>

<script src="https://link.msgsndr.com/js/form_embed.js" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const backBtn = document.getElementById('booking-back-btn');
  if (backBtn){
    backBtn.addEventListener('click', () => {
      if (history.length > 1) history.back(); else location.href = '/';
    });
  }

  const iframe   = document.getElementById('booking-iframe');
  const loader   = document.getElementById('booking-loader');
  const fallback = document.getElementById('booking-fallback-link');

  let loaded = false;
  loader.style.display = 'flex';

  iframe.addEventListener('load', function(){
    loaded = true;
    setTimeout(() => {
      loader.style.display = 'none';
      loader.setAttribute('aria-busy','false');
    }, 450);

    // After iframe loads, attempt to postMessage prefill data (best-effort)
    try {
      const prefill = {
        first_name: '{{ addslashes(auth()->user()->name ? preg_split('/\\s+/', trim(auth()->user()->name), 2)[0] : '') }}',
        last_name: '{{ addslashes(auth()->user()->name ? (preg_split('/\\s+/', trim(auth()->user()->name), 2)[1] ?? '') : '') }}',
        email: '{{ addslashes(auth()->user()->email ?? '') }}',
        phone: '{{ addslashes(auth()->user()->phone ?? '') }}'
      };

      const targets = ['prefill', 'leadconnector', 'booking_prefill', 'GHLPrefill', 'prefillData'];
      let attempts = 0;
      const sendPrefill = () => {
        attempts++;
        targets.forEach(t => {
          iframe.contentWindow.postMessage({type: t, data: prefill}, '*');
        });
        if (attempts < 4) setTimeout(sendPrefill, 400);
      };
      sendPrefill();
    } catch (e) {
      // ignore cross-origin errors or if iframe not ready
      console.debug('prefill postMessage failed', e);
    }
  });

  setTimeout(() => {
    if (!loaded) {
      loader.style.display = 'none';
      if (fallback) fallback.classList.add('animate-pulse');
    }
  }, 10000);

  // Analytics hook
  const IO = new IntersectionObserver(entries=>{
    entries.forEach(e=>{
      if(e.isIntersecting){
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({event:'calendar_viewed'});
        IO.disconnect();
      }
    });
  }, { threshold: 0.25 });
  IO.observe(iframe);
});
</script>
@endsection
