@extends('layouts.app')

@section('title', $marketing->get('meta.title', 'Agent Bookr — Scraping Suite for High-Quality Real Estate Leads'))

@section('content')
    <!-- Design tokens for easy theming -->
    <style>
:root {
  --brand-50:  #f0fdf4;
  --brand-100: #dcfce7;
  --brand-200: #bbf7d0;
  --brand-300: #86efac;
  --brand-400: #4ade80;
  /* NEW: match “New scrape” */
  --brand-500: #10b981; /* emerald-500 */
  --brand-600: #059669; /* emerald-600 */
  --brand-700: #047857; /* emerald-700 */
  --brand-800: #166534;
  --brand-900: #14532d;
  --ink-900:  #0b1722;
}
        /* ===== Smooth in-page scrolling ===== */
        html { scroll-behavior: smooth; }
        /* Offset anchor landing to account for fixed phone bar + header (~32px + ~72px) */
        .scroll-target { scroll-margin-top: 120px; }

        /* ===== SEGWAY ANIMATIONS (drop-in) ===== */
        :root{
          --segway-ease: cubic-bezier(.2,.8,.2,1);
          --segway-duration: 700ms;
          --segway-stagger: 90ms;
        }

        /* Section wrapper enables overflow effects */
        .segway{ position: relative; overflow: clip; }

        /* subtle parallax layer (optional) */
        .seg-parallax{
          position:absolute; inset:-8% -0% auto -0%; height:40%;
          background: radial-gradient(60% 60% at 15% 10%, rgba(76,175,80,.08), transparent 60%),
                      radial-gradient(50% 50% at 85% -5%, rgba(16,185,129,.10), transparent 60%);
          transform: translateY(18px);
          opacity: .0;
          pointer-events:none;
          transition: transform 800ms var(--segway-ease), opacity 800ms var(--segway-ease);
        }
        .segway.is-inview .seg-parallax{ transform: translateY(0); opacity: 1; }

        .aquarium {
  position: relative;
  overflow: hidden;
  pointer-events: none;               /* blocks clicks inside */
  background:
    radial-gradient(1200px 200px at 120% -10%, rgba(255,255,255,0.7), transparent 60%),
    linear-gradient(180deg, rgba(0,150,136,0.06), rgba(0,150,136,0.03));
}
.aquarium * { pointer-events: none !important; } /* belt + suspenders */

/* Glassy rim + subtle inner glow */
.aquarium::before, .aquarium::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
}
.aquarium::before { /* soft inner shadow */
  box-shadow: inset 0 0 40px rgba(0,0,0,0.06);
}
.aquarium::after {  /* gentle water shine + floating bubbles */
  background:
    radial-gradient(8px 8px at 15% 85%, rgba(255,255,255,0.35), transparent 60%),
    radial-gradient(6px 6px at 70% 20%, rgba(255,255,255,0.25), transparent 60%),
    radial-gradient(10px 10px at 40% 100%, rgba(255,255,255,0.3), transparent 60%),
    linear-gradient(110deg, rgba(255,255,255,0.10), rgba(255,255,255,0));
  animation: floaty 14s ease-in-out infinite;
  mix-blend-mode: screen;
  opacity: 0.8;
}
@keyframes floaty {
  0%, 100% { transform: translateY(0px); }
  50%      { transform: translateY(-6px); }
}

/* Make disabled controls look obviously read-only */
.aquarium input[disabled] {
  background: #f8fafc;
  color: #64748b;
}
.aquarium .btn,
.aquarium [class*="btn"] {
  box-shadow: none !important;
  transform: none !important;
}
        /* Gradient wipe that kisses the top border of each section */
        .seg-wipe{
          content:""; position:absolute; left:0; right:0; top:-1px; height:22px;
          background: linear-gradient(180deg, rgba(16,185,129,.14), rgba(16,185,129,0));
          filter: blur(6px); opacity:.0; transform: translateY(-8px);
          transition: opacity 600ms var(--segway-ease), transform 600ms var(--segway-ease);
        }
        .segway::before{ display:block; }
        .segway.is-inview::before{ content: var(--seg-wipe, ""); }
        .segway.is-inview .seg-wipe{ opacity:1; transform: translateY(0); }

        /* ---- Reveal primitives (compose these on elements) ---- */
        .reveal-up, .reveal-right, .reveal-scale, .reveal-fade{
          opacity:0; transform-origin: 50% 60%;
          transition: transform var(--segway-duration) var(--segway-ease),
                      opacity   var(--segway-duration) var(--segway-ease),
                      filter    var(--segway-duration) var(--segway-ease);
          will-change: transform, opacity, filter;
        }
        .reveal-up    { transform: translateY(24px); }
        .reveal-right { transform: translateX(-22px); }
        .reveal-scale { transform: scale(.96); filter: blur(4px); }
        .reveal-fade  { transform: none; }

        .is-inview .reveal-up,
        .is-inview .reveal-right,
        .is-inview .reveal-scale,
        .is-inview .reveal-fade{
          opacity:1; transform: translate(0,0) scale(1); filter: blur(0);
        }

        /* Stagger: apply via style="--i:0" (0,1,2...) on siblings */
        [style*="--i:"]{ transition-delay: calc(var(--i) * var(--segway-stagger)); }

        /* Card hover polish (works with your existing .card-hover) */
        .card-hover{ transition: transform .35s var(--segway-ease), box-shadow .35s var(--segway-ease); }
        .card-hover:hover{ transform: translateY(-4px); box-shadow: 0 16px 40px rgba(20,83,45,.12); }

        /* Optional: scroll cue (tiny nudge at bottom of hero) */
        .scroll-cue{
          position:absolute; left:50%; bottom:14px; width:2px; height:24px;
          background: currentColor; opacity:.25; border-radius:999px; transform: translateX(-50%);
          animation: cue 1.8s ease-in-out infinite;
        }
        @keyframes cue{
          0%{ transform: translate(-50%,0); opacity:.25; }
          40%{ transform: translate(-50%,8px); opacity:.55; }
          100%{ transform: translate(-50%,0); opacity:.25; }
        }

        /* Hero visuals */
        .hero-wrap { position: relative; overflow: hidden;
          background:
            radial-gradient(1200px 600px at 10% -10%, var(--brand-100), transparent),
            radial-gradient(900px 500px at 90% -20%, var(--brand-200), transparent),
            linear-gradient(180deg, white 0%, #f8fafc 100%);
        }
        .hero-grid:before {
            content:""; position:absolute; inset:0;
            background-image:
              linear-gradient(to right, rgba(20,83,45,.06) 1px, transparent 1px),
              linear-gradient(to bottom, rgba(20,83,45,.06) 1px, transparent 1px);
            background-size:32px 32px;
            mask-image: radial-gradient(closest-side, #000, transparent 85%);
            pointer-events:none;
        }
        @keyframes float { 0%{transform:translateY(0)} 50%{transform:translateY(-8px)} 100%{transform:translateY(0)} }
        .float{ animation: float 7s ease-in-out infinite; }
        .glass{ backdrop-filter:saturate(140%) blur(8px); background: rgba(255,255,255,.75); }
        .shadow-soft{ box-shadow: 0 8px 30px rgba(22,101,52,.09); }
        .ring-brand{ box-shadow: 0 0 0 10px rgba(16,185,129,.08); }
        .card-hover{ transition: transform .35s cubic-bezier(.2,.8,.2,1), box-shadow .35s; }
        .card-hover:hover{ transform: translateY(-4px); box-shadow: 0 16px 40px rgba(20,83,45,.12); }

        /* ===== Button system (local fix; also added site-wide in header below) ===== */
        .btn{
          display:inline-flex; align-items:center; justify-content:center; gap:.6rem;
          font-weight:600; border-radius:.9rem; padding:.9rem 1.25rem;
          transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease, color .2s ease, border-color .2s ease;
        }
        .btn svg{ width:1.1rem; height:1.1rem; }
        .btn:hover{ transform: translateY(-1px); box-shadow: 0 10px 24px rgba(16,185,129,.20); }

        /* Primary */
        .btn-primary{ background: var(--brand-600); color:#fff; }
        .btn-primary:hover{ background: var(--brand-700); color:#fff !important; }
        html.dark .btn-primary{ background: var(--ab-primary); color:#fff; }
        html.dark .btn-primary:hover{ background: var(--ab-primary-700); color:#fff !important; }

        /* Ghost */
        .btn-ghost{ background:#fff; color: var(--brand-700); border:1px solid var(--brand-200); }
        .btn-ghost:hover{ background: var(--brand-50); color: var(--brand-700); }
        html.dark .btn-ghost{ background: var(--ab-card); color: var(--ab-text); border:1px solid var(--ab-border); }
        html.dark .btn-ghost:hover{ background: color-mix(in oklab, var(--ab-card) 88%, transparent); color: var(--ab-text); }

        /* Invert: light = white; dark = green */
        .btn-invert{
          background:#fff; color: var(--brand-800); border:1px solid var(--brand-200);
        }
        .btn-invert:hover{ background:#f8fafc; color: var(--brand-800); }
        html.dark .btn-invert{
          background: var(--ab-primary); color:#fff; border-color: transparent;
          box-shadow: 0 10px 24px rgba(16,185,129,.22);
        }
        html.dark .btn-invert:hover{ background: var(--ab-primary-700); color:#fff; }

        /* Prevent global link hovers from recoloring buttons (local guard) */
        a.btn:hover{ color: inherit; }
        .btn-primary:hover{ color:#fff !important; }

        .badge { color: var(--brand-800); background: var(--brand-100); border: 1px solid var(--brand-200); }
        pre[class*="language-"] { background:#0b1722; color:#e2e8f0; border-radius:14px; padding:1rem 1.25rem; overflow:auto; }

        /* ===== THEME-AWARE SHIM for HOW-IT-WORKS + FAQS ===== */
        .howit, .faqwrap { transition: background-color .35s, color .35s, border-color .35s; }
        .howit, .faqwrap { background: var(--brand-50); }
        html.dark .howit, html.dark .faqwrap{
          background: linear-gradient(180deg, var(--hero-top) 0%, var(--hero-bottom) 100%);
        }
        html.dark .howit .text-slate-600,
        html.dark .howit .text-slate-500,
        html.dark .faqwrap .text-slate-600,
        html.dark .faqwrap .text-slate-500{ color: var(--ab-muted) !important; }
        html.dark .faqwrap details.group{
          background: var(--ab-card) !important; border-color: var(--ab-border) !important; color: var(--ab-text) !important;
        }
        html.dark .faqwrap details.group summary svg{ color: var(--ab-primary) !important; stroke: var(--ab-primary) !important; }
    </style>

    <!-- Hero -->
    <section class="hero-wrap hero-grid relative pt-28 pb-20 md:pt-32 md:pb-28 segway">
        <i class="seg-parallax" aria-hidden="true"></i>
        <span class="seg-wipe" aria-hidden="true"></span>

        <div class="container mx-auto px-6 max-w-7xl">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="relative reveal-up">
                    <span class="inline-flex items-center badge px-3 py-1 rounded-full text-xs tracking-wide mb-5">{{ $marketing->get('tagline', 'White-label scraping for serious deal flow') }}</span>
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-[1.05] text-[color:var(--ink-900)]">
                        {{ $marketing->get('headlines.hero_h1', 'Elevate 100% of your deals with') }}<br>
                        <span class="text-[color:var(--brand-700)]">{{ $marketing->get('headlines.hero_h2', 'AgentBookr.') }}</span>
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-slate-600">
                        {{ $marketing->get('hero.blurb', 'Point-and-click selectors, rotating proxies, and scheduled runs—built for real estate lead discovery and enrichment. Export clean data straight to your CRM.') }}
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('book') }}" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                            {{ $marketing->get('cta.hero', 'Start free trial') }}
                        </a>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('pricing') ? route('pricing') : url('/pricing') }}" class="btn btn-invert">
                            View pricing
                        </a>
                    </div>
                    <div class="mt-8 flex items-center gap-6 text-sm text-slate-600">
                        <div class="flex -space-x-3">
                            <img class="w-9 h-9 rounded-full ring-2 ring-white ring-offset-2 ring-offset-white" src="https://images.unsplash.com/photo-1544723795-3fb6469f5b39?q=80&w=120&auto=format&fit=crop" alt=""/>
                            <img class="w-9 h-9 rounded-full ring-2 ring-white ring-offset-2 ring-offset-white" src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?q=80&w=120&auto=format&fit=crop" alt=""/>
                            <img class="w-9 h-9 rounded-full ring-2 ring-white ring-offset-2 ring-offset-white" src="https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?q=80&w=120&auto=format&fit=crop" alt=""/>
                        </div>
                        <p><span class="font-semibold text-[color:var(--brand-700)]">10k+</span> scrapes/day with 99.9% success</p>
                    </div>
                </div>

                <!-- Visual -->
                <div class="relative reveal-right">
                    <div class="glass ring-brand rounded-2xl p-4 md:p-6 shadow-soft card-hover">
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div class="rounded-xl bg-white border border-[color:var(--brand-100)] p-4">
                                <div class="text-xs text-slate-500">Success rate</div>
                                <div class="text-2xl font-bold text-[color:var(--brand-800)]">99.9%</div>
                            </div>
                            <div class="rounded-xl bg-white border border-[color:var(--brand-100)] p-4">
                                <div class="text-xs text-slate-500">Average latency</div>
                                <div class="text-2xl font-bold text-[color:var(--brand-800)]">1.2s</div>
                            </div>
                            <div class="rounded-xl bg-white border border-[color:var(--brand-100)] p-4">
                                <div class="text-xs text-slate-500">Records today</div>
                                <div class="text-2xl font-bold text-[color:var(--brand-800)]">57,842</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <i class="scroll-cue" aria-hidden="true"></i> -->
    </section>

    <!-- Brand Logos (sexy glass cards, row of 3) -->
    <section class="py-14 brand-showcase">
      <style>
        /* Scoped to this section only */
        .brand-showcase .eyebrow{
          letter-spacing:.18em; text-transform:uppercase; color:#64748b;
        }
        .brand-showcase .rail{
          display:grid; grid-template-columns:repeat(3,minmax(0,1fr));
          gap:1.25rem; align-items:stretch; justify-items:center;
        }
        @media (max-width: 768px){
          .brand-showcase .rail{ gap:.9rem; }
        }
        .brand-showcase .card{
          --r:18px;
          position:relative; width:100%; max-width:380px; min-height:124px;
          border-radius:var(--r); padding:22px 24px;
          background:
            linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,.84)) padding-box,
            linear-gradient(135deg, rgba(16,185,129,.28), rgba(59,130,246,.22)) border-box;
          border:1px solid transparent;
          box-shadow: 0 12px 34px rgba(17,94,89,.10), inset 0 1px 0 rgba(255,255,255,.6);
          backdrop-filter: blur(8px) saturate(130%);
          display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.65rem;
          transition: transform .28s cubic-bezier(.2,.8,.2,1), box-shadow .28s, border-color .28s;
          overflow:hidden;
        }
        .brand-showcase .card:hover{
          transform: translateY(-4px);
          box-shadow: 0 18px 54px rgba(20,83,45,.14);
          border-color: rgba(34,197,94,.45);
        }
        .brand-showcase .logo{ height:38px; width:auto; opacity:.92; filter:saturate(.95) contrast(1.05); }
        .brand-showcase .card:hover .logo{ opacity:1; filter:none; }
        .brand-showcase .label{
          font-weight:700; font-size:.85rem;
        }
        .brand-showcase .fb { color:#1877F2; }          /* Facebook */
        .brand-showcase .ki { color:#5f5f5f; }          /* Kijiji gray */
        .brand-showcase .zi { color:#0f294c; }          /* Zillow navy */
      </style>

      <div class="container mx-auto px-6 max-w-7xl">
        <div class="text-center mb-8">
          <p class="eyebrow text-sm">Scraping data straight from the source</p>
        </div>

        <div class="rail">
          <div class="card">
            <img src="{{ asset('images/facebook-svgrepo-com.svg') }}" alt="Facebook" class="logo" loading="lazy">
          </div>

          <div class="card">
            <img src="{{ asset('images/brand-zillow-svgrepo-com.svg') }}" alt="Zillow" class="logo" loading="lazy">
          </div>

          <div class="card">
            <img src="{{ asset('images/kijiji-svgrepo-com.svg') }}" alt="Kijiji" class="logo" loading="lazy">
          </div>
        </div>
      </div>
    </section>

    <!-- Feature grid = SOLUTIONS -->
<section id="solutions" class="py-20 bg-white segway scroll-target">
    <span class="seg-wipe" aria-hidden="true"></span>
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="text-center max-w-3xl mx-auto reveal-up">
            <h2 class="text-3xl md:text-5xl font-extrabold text-[color:var(--ink-900)]">More listings. More appointments. Less busywork.</h2>
            <p class="mt-4 text-lg text-slate-600">Built for agents and teams: fresh owners, buyers, and landlords—complete with clean phone numbers, verified emails, and quick comps—ready to call today.</p>
        </div>
        <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['title' => 'Find the right people fast', 'desc' => 'Pull FSBOs, expireds, landlords, investors, and buyers in the neighborhoods you farm.'],
                ['title' => 'Reach out with confidence', 'desc' => 'Clean, ready-to-use phone numbers and emails so your calls connect and your emails land.'],
                ['title' => 'Own your market', 'desc' => 'Zero in by ZIP, school district, price range, days on market, or “needs work” notes.'],
                ['title' => 'Leads while you show homes', 'desc' => 'New contacts show up on your schedule—daily or weekly—without the copy-and-paste grind.'],
                ['title' => 'Stay compliant and professional', 'desc' => 'Optional DNC flags and opt-out tracking help you prospect the right way.'],
                ['title' => 'Drop straight into your workflow', 'desc' => 'One click to send to Salesforce, HubSpot, Follow Up Boss, or your dialer—Mojo, PhoneBurner, and more.'],
            ] as $f)
                <div class="rounded-2xl border border-[color:var(--brand-100)] p-6 card-hover bg-white reveal-scale" style="--i:{{ $loop->index }}">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background: var(--brand-50); border:1px solid var(--brand-200)">
                        <svg class="w-5 h-5 text-[color:var(--brand-700)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8 10-5-5"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[color:var(--ink-900)]">{{ $f['title'] }}</h3>
                    <p class="mt-2 text-slate-600">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>


    <!-- How it works  -->
<section class="howit py-20 bg-[color:var(--brand-50)] segway scroll-target">
  <span class="seg-wipe" aria-hidden="true"></span>
  <div class="container mx-auto px-6 max-w-7xl">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <!-- Left: steps -->
      <div class="reveal-up">
        <h2 class="text-3xl md:text-5xl font-extrabold text-[color:var(--ink-900)]">From Kijiji search to call list in minutes</h2>
        <p class="mt-4 text-lg text-slate-600">Find owners, landlords, and buyers on Kijiji, enrich with phones and emails, and send them straight to your follow-up list.</p>

        <div class="mt-8 space-y-6">
          @foreach([
            ['n' => '1', 't' => 'Pick your Kijiji results',  'd' => 'Filter by city, price, bedrooms, or keywords like “FSBO” and copy the results link.'],
            ['n' => '2', 't' => 'Choose what you want',      'd' => 'Grab names, phone numbers, emails, prices, addresses, and the listing link.'],
            ['n' => '3', 't' => 'Let it collect for you',     'd' => 'It pulls the matches and fills in your list while you handle appointments.'],
            ['n' => '4', 't' => 'Download & send to your tools','d' => 'Save as a spreadsheet or push into your CRM or dialer in one step.'],
          ] as $s)
            <div class="flex gap-4 items-start reveal-up" style="--i:{{ $loop->index }}">
              <div class="w-10 h-10 flex-shrink-0 rounded-full flex items-center justify-center font-bold text-white"
                   style="background: var(--brand-600)">{{ $s['n'] }}</div>
              <div class="pt-0.5">
                <h3 class="font-semibold leading-tight text-[color:var(--ink-900)]">{{ $s['t'] }}</h3>
                <p class="text-slate-600 mt-1">{{ $s['d'] }}</p>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-8">
          <a href="{{ route('book') }}" class="btn btn-primary">Schedule a demo</a>
        </div>
      </div>

      <!-- Right: tiny no-code demo -->
      <div class="reveal-right" style="--i:0">
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 aquarium" aria-label="Read-only demo">
    <div class="flex items-center justify-between mb-4">
      <!-- <span class="text-xs px-2 py-1 rounded-full bg-[color:var(--brand-100)] text-[color:var(--brand-700)]">Demo</span> -->
    </div>

    <!-- URL input (read-only) -->
    <label class="text-sm text-slate-600">Kijiji search link</label>
    <div class="mt-1 flex rounded-lg ring-1 ring-slate-200 overflow-hidden">
      <input id="demoUrl"
             class="w-full px-3 py-2 outline-none opacity-70 cursor-default select-none"
             value="{{ $marketing->get('job.example_url', 'https://www.kijiji.ca/b-real-estate/city-of-toronto/c34l1700273?ad=offering&forsale-by=ownr') }}"
             readonly disabled aria-disabled="true">
      <span class="px-3 py-2 text-white opacity-60 cursor-default select-none"
            style="background: var(--brand-600)">Preview</span>
    </div>

    <!-- Field chips (read-only) -->
    <div id="demoFields" class="mt-4">
      <p class="text-sm text-slate-600 mb-2">What we’ll pull</p>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        @foreach(['Name','Phone','Email','Price','Address','Listing URL','and much more!'] as $f)
          <span class="text-sm px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 bg-slate-50 opacity-80">{{ $f }}</span>
        @endforeach
      </div>
    </div>

    <!-- Actions (decorative only) -->
    <!-- <div class="mt-5 flex flex-wrap items-center gap-3"> -->
      <!-- <span class="btn btn-primary opacity-60 cursor-default select-none">Start</span>
      <span class="btn btn-ghost opacity-60 cursor-default select-none">Download spreadsheet</span> -->
    <!-- </div> -->

    <!-- Status -->
    <!-- <div id="demoStatus" class="mt-3 text-sm text-slate-500">Watching listings… (read-only preview)</div> -->
  </div>

  <div class="mt-3 text-xs text-slate-500">
    Example only. The full version gathers more details, runs on your schedule, and creates CRM-ready results.
  </div>
</div>
    </div>
  </div>
<!-- </section> -->


  <script>
    function demoExtract(){
      document.getElementById('demoFields').classList.remove('hidden');
      const runBtn = document.getElementById('demoRunBtn');
      runBtn.disabled = false;
      document.getElementById('demoStatus').textContent = 'Selectors mapped. Ready to run.';
    }
    function demoRun(){
      const status = document.getElementById('demoStatus');
      const fill   = document.getElementById('demoFill');
      const csvBtn = document.getElementById('demoCsvBtn');
      let p = 0;
      status.textContent = 'Searching listings…';
      const timer = setInterval(() => {
        p = Math.min(100, p + Math.floor(Math.random()*12)+5);
        fill.style.width = p + '%';
        if (p < 40)      status.textContent = 'Searching listings…';
        else if (p < 75) status.textContent = 'Processing & extracting…';
        else if (p < 100)status.textContent = 'Enriching & validating…';
        if (p >= 100) {
          clearInterval(timer);
          status.textContent = 'Complete — sample leads ready.';
          csvBtn.classList.remove('hidden');
        }
      }, 280);
    }
  </script>
</section>


    <!-- Pricing CTA -->
    <section id="pricing" class="relative py-20 bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 overflow-hidden segway">
        <span class="seg-wipe" aria-hidden="true"></span>
        <div class="absolute inset-0 opacity-10" aria-hidden="true">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 10%, rgba(255,255,255,.25) 0 40%, transparent 41%), radial-gradient(circle at 80% 20%, rgba(255,255,255,.25) 0 40%, transparent 41%);"></div>
        </div>
        <div class="container mx-auto px-6 max-w-5xl relative z-10 text-center reveal-up">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white">See plans & pricing</h2>
            <p class="mt-4 text-white/80">{{ $marketing->get('pricing.blurb', 'Display-only plans. Book a call to get started.') }}</p>
            <a id="about" href="{{ \Illuminate\Support\Facades\Route::has('pricing') ? route('pricing') : url('/pricing') }}" class="btn btn-invert mt-8">View pricing</a>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-white segway">
        <span class="seg-wipe" aria-hidden="true"></span>
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center max-w-3xl mx-auto reveal-up">
                <h2 class="text-3xl md:text-5xl font-extrabold text-[color:var(--ink-900)]">Trusted by growth teams</h2>
                <p class="mt-4 text-lg text-slate-600">What customers say after switching to Agent Bookr.</p>
            </div>
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                @foreach([
                    ['n' => 'Sarah M.', 'q' => 'We replaced a patchwork of scripts with one platform. Leads doubled in 90 days.'],
                    ['n' => 'Mike R.', 'q' => 'The scheduler + dedupe alone paid for the year. Zero babysitting.'],
                    ['n' => 'Jennifer L.', 'q' => 'Exported to HubSpot with one click. Our SDRs love the quality.'],
                ] as $c)
                    <figure class="rounded-2xl border border-[color:var(--brand-100)] p-6 bg-white card-hover reveal-scale" style="--i:{{ $loop->index }}">
                        <blockquote class="text-slate-700">“{{ $c['q'] }}”</blockquote>
                        <figcaption class="mt-4 font-semibold text-[color:var(--ink-900)]">{{ $c['n'] }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQs -->
    @if (is_array($marketing->get('faqs')) && count($marketing->get('faqs')))
    <section class="faqwrap py-20 bg-[color:var(--brand-50)] segway">
        <span class="seg-wipe" aria-hidden="true"></span>
        <div class="container mx-auto px-6 max-w-4xl reveal-up">
            <h2 class="text-3xl font-extrabold text-[color:var(--ink-900)] mb-6">Frequently asked questions</h2>
            <div class="space-y-4">
                @foreach ($marketing->get('faqs') as $faq)
                    <details class="group rounded-xl border border-[color:var(--brand-100)] bg-white p-4 reveal-scale" style="--i:{{ $loop->index }}">
                        <summary class="flex items-center justify-between cursor-pointer select-none">
                            <span class="font-semibold text-[color:var(--ink-900)]">{{ data_get($faq, 'question') }}</span>
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 text-[color:var(--brand-700)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </summary>
                        <div class="mt-2 text-slate-600">{!! nl2br(e(data_get($faq, 'answer'))) !!}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@else
    <section class="faqwrap py-20 bg-[color:var(--brand-50)] segway">
        <span class="seg-wipe" aria-hidden="true"></span>
        <div class="container mx-auto px-6 max-w-4xl reveal-up">
            <h2 class="text-3xl font-extrabold text-[color:var(--ink-900)] mb-6">Frequently asked questions</h2>
            <div class="space-y-4">
                @foreach ([
                    ['question' => 'Is scraping legal?', 'answer' => 'Always follow terms of service and applicable law. We offer robots.txt respect, rate-limit controls, and compliance tooling to help you operate responsibly.'],
                    ['question' => 'Do you provide proxies?', 'answer' => 'Yes—geo-targeted residential pools with automatic rotation and fallback. Bring your own if you prefer.'],
                    ['question' => 'Can I push to my CRM?', 'answer' => 'One-click exports to HubSpot/Salesforce or custom webhooks. De-duplication included.'],
                ] as $faq)
                    <details class="group rounded-xl border border-[color:var(--brand-100)] bg-white p-4 reveal-scale" style="--i:{{ $loop->index }}">
                        <summary class="flex items-center justify-between cursor-pointer select-none">
                            <span class="font-semibold text-[color:var(--ink-900)]">{{ data_get($faq, 'question') }}</span>
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180 text-[color:var(--brand-700)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </summary>
                        <div class="mt-2 text-slate-600">{!! nl2br(e(data_get($faq, 'answer'))) !!}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endif

    <!-- Final CTA -->
    <!-- <section class="py-20 bg-white segway">
        <span class="seg-wipe" aria-hidden="true"></span>
        <div class="container mx-auto px-6 max-w-5xl reveal-up">
            <div class="rounded-3xl p-10 md:p-14 bg-gradient-to-r from-[color:var(--brand-600)] to-[color:var(--brand-700)] text-white shadow-soft">
                <div class="grid md:grid-cols-[1.2fr_.8fr] gap-10 items-center">
                    <div>
                        <h3 class="text-3xl md:text-4xl font-extrabold">Ready to turn messy pages into clean, actionable leads?</h3>
                        <p class="mt-3 text-white/90">Launch your first job in minutes—no code required. Cancel anytime.</p>
                    </div>
                    <div class="text-right md:text-right">
                        <a href="{{ route('book') }}" class="btn btn-invert">{{ $marketing->get('cta.tenant', 'Get started') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <script>
      // Reveal on scroll with IntersectionObserver
      (() => {
        const io = new IntersectionObserver((entries) => {
          entries.forEach(e => {
            if (e.isIntersecting) {
              e.target.classList.add('is-inview');
              // Unobserve once revealed for perf + one-shot entrance
              io.unobserve(e.target);
            }
          });
        }, { rootMargin: "0px 0px -10% 0px", threshold: 0.15 });

        // Observe all segway sections
        document.querySelectorAll('.segway').forEach(el => io.observe(el));
      })();
    </script>
@endsection
