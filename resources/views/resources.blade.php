@extends('layouts.app')

@section('title', 'Resources — Agent Bookr')

@section('content')
<style>
  /* =====================  TOKENS  ===================== */
  :root{
    --brand-50:#f0fdf4; --brand-100:#dcfce7; --brand-200:#bbf7d0; --brand-300:#86efac;
    --brand-400:#34d399; 
    /* New-scrape emeralds */
    --brand-500:#10b981; /* ✅ bright */
    --brand-600:#059669; /* ✅ deep */
    --brand-700:#047857;
    --brand-800:#166534; --brand-900:#14532d;

    --ink-900:#0b1722; --ink-700:#1f2937; --ink-600:#475569; --ink-500:#64748b;
    --surface:#ffffff; --muted:#f8fafc;
  }

  /* =====================  GLOBAL  ===================== */
  .container-narrow{ max-width:1100px }
  .section{ padding-block: clamp(2.2rem, 1.6rem + 1.6vw, 3rem) }
  .section-brand{ padding-block: clamp(5rem, 3rem + 6vw, 10rem) }

  .display-hero{ font-size: clamp(2.1rem, 1.2rem + 2.8vw, 3.35rem); line-height:1.06; letter-spacing:-.01em }
  .display-h2{ font-size: clamp(1.35rem, 1.1rem + .9vw, 2.05rem); line-height:1.12; letter-spacing:-.01em }

  .bg-hero{
    background:
      radial-gradient(1200px 560px at 8% -12%, var(--brand-100), transparent 60%),
      radial-gradient(900px 420px at 94% -16%, var(--brand-300), transparent 60%),
      radial-gradient(800px 520px at 20% 120%, rgba(34,197,94,.10), transparent 60%),
      linear-gradient(180deg,#fff,var(--muted));
    position:relative; overflow:hidden;
  }
  .bg-hero:after{
    content:""; position:absolute; inset:-1px; pointer-events:none;
    background: repeating-linear-gradient(90deg, rgba(2,6,12,.015) 0 2px, transparent 2px 4px),
                repeating-linear-gradient(0deg, rgba(2,6,12,.015) 0 2px, transparent 2px 4px);
    mix-blend-mode:multiply; opacity:.35;
    mask-image: radial-gradient(closest-side, #000 70%, transparent 100%);
  }

  .grid-mask:before{
    content:""; position:absolute; inset:0; pointer-events:none; opacity:.7;
    background-image:
      linear-gradient(to right, rgba(20,83,45,.05) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(20,83,45,.05) 1px, transparent 1px);
    background-size:28px 28px;
    mask-image: radial-gradient(closest-side, #000, transparent 78%);
  }

  .btn{
    display:inline-flex; align-items:center; gap:.6rem; font-weight:800;
    border-radius:14px; padding:.85rem 1.1rem; letter-spacing:.01em;
    transition: transform .18s, box-shadow .18s, filter .18s;
  }
  .btn svg{ width:1.05rem; height:1.05rem }

  /* ✅ Use the new-scrape gradient */
  .btn-primary{
    color:#fff; 
    background:
      linear-gradient(180deg, rgba(255,255,255,.12), rgba(0,0,0,.06)) padding-box,
      linear-gradient(135deg, var(--brand-500), var(--brand-600)) border-box;
    border:1px solid transparent;
    box-shadow: 0 12px 26px rgba(16,185,129,.25), inset 0 -2px 0 rgba(0,0,0,.15);
  }
  .btn-primary:hover{ transform: translateY(-1px); filter:saturate(1.05) brightness(.98) }
  .btn:focus-visible{ outline:2px solid var(--brand-400); outline-offset:2px }

  .eyebrow{ text-transform:uppercase; letter-spacing:.18em; color:#64748b; font-weight:800; font-size:.8rem }
  .badge{
    display:inline-flex; align-items:center; gap:.4rem; font-weight:700; font-size:.75rem;
    color:#065f46; background:linear-gradient(180deg,#ecfdf5,#d1fae5); border:1px solid #a7f3d0;
    border-radius:999px; padding:.35rem .65rem;
  }

  .card{
    position:relative; border-radius:18px; overflow:hidden;
    background:
      linear-gradient(180deg, rgba(255,255,255,.9), rgba(255,255,255,.8)) padding-box,
      linear-gradient(135deg, var(--brand-100), rgba(15,23,42,.1)) border-box;
    border:1px solid transparent; backdrop-filter: blur(10px) saturate(120%);
    box-shadow:0 16px 42px rgba(2,44,34,.10);
    transition: transform .22s cubic-bezier(.2,.8,.2,1), box-shadow .22s, filter .22s, border-color .22s;
  }
  .card:hover{ transform: translateY(-4px); box-shadow: 0 24px 56px rgba(2,44,34,.16) }

  .bullets{ display:grid; gap:.65rem; grid-template-columns:1fr }
  @media (min-width:640px){ .bullets{ grid-template-columns:repeat(2,minmax(0,1fr)) } }
  .bullet{ display:flex; align-items:flex-start; gap:.55rem; font-size:.97rem; color:var(--ink-600) }
  .bullet svg{ flex:none; width:1.1rem; height:1.1rem; color:var(--brand-600) } /* <- new deep emerald */

  .hairline{
    height:1px; background:
    linear-gradient(90deg, transparent, rgba(16,185,129,.25), transparent);
    mask-image: linear-gradient(90deg, transparent, #000 25%, #000 75%, transparent);
  }

  .reveal{ opacity:0; transform: translateY(14px); transition: opacity .5s ease, transform .5s ease }
  .reveal.in{ opacity:1; transform:none }
  @media (prefers-reduced-motion: reduce){
    .reveal{ opacity:1; transform:none }
    .card, .btn{ transition:none }
  }

  .tilt{ transform-style: preserve-3d; will-change: transform }
</style>

<!-- =====================  HERO  ===================== -->
<section class="bg-hero relative pt-32 pb-12 md:pt-44 md:pb-32">
  <div class="grid-mask absolute inset-0"></div>
  <div class="container mx-auto px-6 container-narrow relative">
    <header class="text-center max-w-3xl mx-auto">
      <span class="eyebrow">Resources</span>
      <h1 class="display-hero mt-2 font-extrabold text-[color:var(--ink-900)] tracking-tight">
        How we source <span class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-800 bg-clip-text text-transparent">ridiculously clean leads</span>
      </h1>
      <p class="mt-5 text-[.99rem] leading-7 text-slate-600">
        A compact guide to pulling high-signal listings straight from the platforms your prospects already use.
      </p>
      <div class="mt-6"><span class="badge">99% FRBO/FSBO</span></div>
    </header>
  </div>
</section>

<div class="hairline"></div>

<!-- =====================  SECTION: Kijiji (BIGGER) ===================== -->
<section class="section section-brand">
  <div class="container mx-auto px-6 container-narrow">
    <div class="grid md:grid-cols-[auto,1fr] gap-10 md:gap-16 items-center">
      <figure class="reveal text-center tilt" data-tilt>
        <img class="inline-block h-12 md:h-16" src="{{ asset('images/kijiji-svgrepo-com.svg') }}" alt="Kijiji">
        <!-- <figcaption class="mt-4 text-xs tracking-wide text-slate-500">Kijiji</figcaption> -->
      </figure>

      <div class="reveal">
        <h2 class="display-h2 font-extrabold text-[color:var(--ink-900)] tracking-tight">
          Kijiji signals you can’t ignore
        </h2>
        <p class="mt-4 text-slate-600">
          Canadian FSBO activity on Kijiji surfaces rentals, flips, and distressed sellers early. We capture titles,
          prices, phones/emails, and location, then dedupe and score so your outreach starts warm.
        </p>
        <ul class="bullets mt-8">
          <li class="bullet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span>Geo-targeted rotating proxies</span>
          </li>
          <li class="bullet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span>Scheduler &amp; alerts for fresh posts</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- =====================  SECTION: Facebook (BIGGER) ===================== -->
<section class="section section-brand">
  <div class="container mx-auto px-6 container-narrow">
    <div class="grid md:grid-cols-[auto,1fr] gap-10 md:gap-16 items-center">
      <figure class="reveal text-center tilt" data-tilt>
        <img class="inline-block h-12 md:h-16" src="{{ asset('images/facebook-svgrepo-com.svg') }}" alt="Facebook">
        <!-- <figcaption class="mt-4 text-xs tracking-wide text-slate-500">Facebook Marketplace</figcaption> -->
      </figure>

      <div class="reveal">
        <h2 class="display-h2 font-extrabold text-[color:var(--ink-900)] tracking-tight">
          Marketplace = motivated owners
        </h2>
        <p class="mt-4 text-slate-600">
          FSBO posts on Facebook often include phone numbers, fresh photos, price drops, and urgency cues. Our selectors
          capture names, phones, addresses, and price edits—then normalize &amp; dedupe before export.
        </p>
        <ul class="bullets mt-8">
          <li class="bullet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span>Auto-pagination &amp; infinite-scroll handling</span>
          </li>
          <li class="bullet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span>Phone/email detection with validation</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<div class="hairline"></div>

<!-- =====================  SECTION: Zillow (BIGGER) ===================== -->
<section class="section section-brand">
  <div class="container mx-auto px-6 container-narrow">
    <div class="grid md:grid-cols-[1fr,auto] gap-10 md:gap-16 items-center">
      <div class="reveal">
        <h2 class="display-h2 font-extrabold text-[color:var(--ink-900)] tracking-tight">
          Zillow comps, pricing, &amp; timelines
        </h2>
        <p class="mt-4 text-slate-600">
          Zillow’s structured details (beds, baths, DOM, price history) are perfect for comps and list hygiene.
          Use the field-mapper to extract what you need and merge to your CRM via webhook or CSV.
        </p>
        <ul class="bullets mt-8">
          <li class="bullet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span>DOM &amp; price-change tracking</span>
          </li>
          <li class="bullet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span>Clean addresses ready for skip-trace</span>
          </li>
        </ul>
      </div>

      <figure class="reveal text-center tilt" data-tilt>
        <img class="inline-block h-12 md:h-16" src="{{ asset('images/brand-zillow-svgrepo-com.svg') }}" alt="Zillow">
        <!-- <figcaption class="mt-4 text-xs tracking-wide text-slate-500">Zillow</figcaption> -->
      </figure>
    </div>
  </div>
</section>

<div class="hairline"></div>

<!-- =====================  COMPLIANCE (unchanged size) ===================== -->
<section class="section pt-3">
  <div class="container mx-auto px-6 container-narrow">
    <div class="card p-6 md:p-8 reveal">
      <p class="text-[1rem] leading-7 text-slate-600">
        Use responsibly. Always respect each site’s terms, applicable law, and do-not-contact preferences.
        Agent Bookr provides robots.txt respect, rate-limits, and tooling to help you operate compliantly.
      </p>
    </div>
  </div>
</section>

<!-- =====================  CTA (unchanged size) ===================== -->
<section class="section bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 text-white">
  <div class="container mx-auto px-6 container-narrow text-center">
    <h3 class="text-[clamp(1.6rem,1.1rem+1.2vw,2.3rem)] font-extrabold tracking-tight">
      Turn raw listings into clean, actionable leads
    </h3>
    <p class="mt-4 text-white/85">See plans and pick the volume that fits your pipeline.</p>
    <a href="{{ \Illuminate\Support\Facades\Route::has('pricing') ? route('pricing') : url('/pricing') }}"
       class="btn btn-primary mt-6">
      View pricing
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
    </a>
  </div>
</section>

<script>
  // Motion-safe reveal on scroll
  (() => {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const els = document.querySelectorAll('.reveal');
    if (!reduce) {
      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }});
      }, { threshold: 0.12, rootMargin: "0px 0px -12% 0px" });
      els.forEach(el => io.observe(el));
    } else {
      els.forEach(el => el.classList.add('in'));
    }
  })();

  // Lightweight 3D tilt on logos (motion-safe)
  (() => {
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduce) return;
    const max = 8; // deg
    document.querySelectorAll('[data-tilt]').forEach(el => {
      let rAF;
      el.addEventListener('mousemove', (e) => {
        const rect = el.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width;
        const y = (e.clientY - rect.top) / rect.height;
        const rx = (y - .5) * -2 * max;
        const ry = (x - .5) *  2 * max;
        cancelAnimationFrame(rAF);
        rAF = requestAnimationFrame(() => { el.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg)`; });
      });
      el.addEventListener('mouseleave', () => { el.style.transform = ''; });
    });
  })();
</script>
@endsection
