@extends('layouts.app')

@section('title', 'Pricing — Agent Bookr')

@section('content')
@php
    /** @var \App\Models\User|null $user */
    $user       = auth()->user();
    $currentTier= $user?->tier; // 'user' | 'paid' | 'admin' | null
    $isAdmin    = $user?->isAdmin() ?? false;
    $isActive   = $user?->hasActiveSubscription() ?? false;

    $labels = ['user' => 'Starter', 'paid' => 'Professional', 'admin' => 'Administrator'];
    $displayTier = fn (?string $tier) => $labels[$tier] ?? 'Unknown';
@endphp

<style>
  /* ========== Sexy green-first look ========== */
  :root{
    --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7;
    --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857;
    --ink:#0b1722; --border:#CAD2C5;
  }
  .pricing-wrap{
    background:
      radial-gradient(900px 420px at 6% -12%, rgba(16,185,129,.10), transparent 60%),
      radial-gradient(900px 420px at 94% -12%, rgba(5,150,105,.10), transparent 60%),
      #fff;
  }
  .grid-mask{
    background-image:
      linear-gradient(to right, rgba(4,120,87,.08) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(4,120,87,.08) 1px, transparent 1px);
    background-size: 32px 32px;
    mask-image: radial-gradient(closest-side, #000, transparent 85%);
    pointer-events:none;
  }

  /* cards */
  .plan-card{
    background:linear-gradient(180deg,#fff 0%, #f8fafc 100%);
    border:1px solid var(--border);
    border-radius:20px;
    box-shadow:0 18px 60px rgba(16,185,129,.10);
    transition:transform .28s cubic-bezier(.2,.8,.2,1), box-shadow .28s cubic-bezier(.2,.8,.2,1), border-color .28s;
  }
  .plan-card:hover{ transform:translateY(-6px); box-shadow:0 24px 70px rgba(4,120,87,.18); border-color:#b7e4d8; }

  /* ribbons + pills */
  .plan-badge{
    background: linear-gradient(135deg, var(--g500), var(--g600));
    color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.28);
  }
  .active-pill{
    background: linear-gradient(135deg, var(--g400), var(--g500));
    color:#062e2a; box-shadow:0 8px 20px rgba(16,185,129,.28);
  }
  .most-pop{ border-width:2px; border-color: var(--g400); }

  .check{ color:#52796F; }
  .price{ color:var(--ink); }
  .muted{ color:#6b7280; }

  /* buttons */
  .btn{ display:inline-flex; align-items:center; justify-content:center; gap:.55rem; font-weight:800; letter-spacing:.01em; border-radius:14px; padding:.95rem 1.15rem; transition:transform .2s, box-shadow .2s, filter .2s; }
  .btn svg{ width:1.05rem; height:1.05rem; }
  .btn-primary{
    background: linear-gradient(135deg, var(--g500), var(--g600));
    color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.22);
  }
  .btn-primary:hover{ filter:brightness(.98); transform:translateY(-1px); color:#fff; }
  .btn-outline{ border:1px solid var(--border); color:var(--ink); background:#fff; }
  .btn-outline:hover{ background:var(--g50); border-color: var(--g300); }

  /* header chip */
  .headline-chip{ background: var(--g100); color: var(--g700); }

  /* account status banner */
  .acct-banner{
    background: linear-gradient(180deg, #fff, var(--g50));
    border:1px solid var(--g200);
    border-radius:16px;
    box-shadow: 0 10px 30px rgba(16,185,129,.10);
  }
  .status-dot{ width:.6rem; height:.6rem; border-radius:999px; display:inline-block; margin-right:.4rem; }
  .status-on { background: var(--g500); box-shadow: 0 0 0 4px rgba(16,185,129,.15); }
  .status-off{ background: #f59e0b; box-shadow: 0 0 0 4px rgba(245,158,11,.15); }

  .kicker{ text-transform:uppercase; letter-spacing:.18em; color:#64748b; }
</style>

<section class="pricing-wrap relative pt-32 pb-20">
  <div class="absolute inset-0 grid-mask"></div>

  <div class="container mx-auto px-6 max-w-6xl relative z-10">
    <!-- Headline -->
    <div class="text-center mb-6">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold headline-chip">
        Display-only plans — book a call to get started
      </span>
      <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight" style="color:var(--ink)">Choose your plan</h1>
      <p class="mt-3 text-lg muted max-w-2xl mx-auto">Designed for agents at every stage — from testing the waters to scaling teams.</p>
    </div>

    <!-- Account status (nicely tucked under headline) -->
    @auth
    <div class="acct-banner p-4 md:p-5 flex flex-col md:flex-row items-center justify-between mb-10">
      <div class="flex items-center gap-3 text-sm">
        <span class="kicker">Account</span>
        <span class="font-semibold">{{ $displayTier($currentTier) }}</span>
        <span class="text-slate-500">•</span>
        @if($isActive)
          <span class="flex items-center font-semibold text-emerald-700"><i class="status-dot status-on"></i>Active</span>
        @else
          <span class="flex items-center font-semibold text-amber-700"><i class="status-dot status-off"></i>Inactive</span>
        @endif
      </div>
<div class="mt-3 md:mt-0 flex gap-2">
  @if(!in_array($currentTier, ['paid', 'admin']) && !$isAdmin)
    <a href="{{ route('book') }}" class="btn btn-outline">Book a Call</a>
  @endif
  {{-- leave other actions commented/removed as needed --}}
  {{-- <a href="{{ route('register') }}" class="btn btn-primary">Upgrade options</a> --}}
</div>
    </div>
    @endauth

    <!-- Plans -->
    <div class="grid md:grid-cols-3 gap-6 md:gap-8">
      {{-- Starter (user) --}}
      @php $isCurrent = $currentTier === 'user' && !$isAdmin; @endphp
      <div class="plan-card p-7 flex flex-col relative">
        @if($isCurrent)
          <span class="absolute -top-3 left-1/2 -translate-x-1/2 active-pill px-3 py-1 rounded-full text-[11px] font-extrabold">ACTIVE PLAN</span>
        @endif

        <div class="text-center mb-6">
          <h3 class="text-xl font-extrabold" style="color:var(--ink)">Starter</h3>
          <div class="mt-1 text-4xl font-black price">$99</div>
          <p class="mt-1 muted">Best for solo agents starting out</p>
        </div>

        <ul class="space-y-3 mb-6 text-[15px]">
          <li class="flex items-start gap-3"><span class="check mt-0.5">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>25 Leads / Week</li>
          <li class="flex items-start gap-3"><span class="check mt-0.5">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Market: Non-exclusive</li>
          <li class="flex items-start gap-3 muted"><span class="check mt-0.5 opacity-60">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Email validation + de-duping</li>
        </ul>

        @if($isCurrent)
          <button class="btn btn-outline mt-auto w-full" disabled>Current Plan</button>
        @else
          @auth
            <a href="{{ route('book') }}" class="btn btn-primary mt-auto w-full text-center">Downgrade</a>
          @else
            <a href="{{ route('register') }}" class="btn btn-primary mt-auto w-full text-center">Start Free Trial</a>
          @endauth
        @endif
      </div>

      {{-- Professional (paid/admin) --}}
      @php $isCurrent = ($currentTier === 'paid') || $isAdmin; @endphp
      <div class="plan-card most-pop p-7 flex flex-col relative md:scale-[1.03]">
        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
          <span class="plan-badge px-4 py-1 rounded-full text-xs font-extrabold tracking-wide">MOST POPULAR</span>
        </div>
        @if($isCurrent)
          <span class="absolute -top-3 right-3 active-pill px-3 py-1 rounded-full text-[11px] font-extrabold">ACTIVE</span>
        @endif

        <div class="text-center mb-6">
          <h3 class="text-xl font-extrabold" style="color:var(--ink)">Professional</h3>
          <div class="mt-1 text-4xl font-black price">$199</div>
          <p class="mt-1 muted">For agents scaling volume</p>
        </div>

        <ul class="space-y-3 mb-6 text-[15px]">
          <li class="flex items-start gap-3"><span class="check mt-0.5">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>50 Leads / Week</li>
          <li class="flex items-start gap-3"><span class="check mt-0.5">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Auto clean, validate, and export</li>
          <li class="flex items-start gap-3 muted"><span class="check mt-0.5 opacity-60">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Priority support</li>
        </ul>

        @if($isCurrent)
          <button class="btn btn-outline mt-auto w-full" disabled>Current Plan</button>
        @else
          @auth
            <a href="{{ route('book') }}" class="btn btn-primary mt-auto w-full text-center">Talk to Sales</a>
          @else
            <a href="{{ route('register') }}" class="btn btn-primary mt-auto w-full text-center">Purchase</a>
          @endauth
        @endif
      </div>

      {{-- Enterprise --}}
      <div class="plan-card p-7 flex flex-col relative">
        <div class="text-center mb-6">
          <h3 class="text-xl font-extrabold" style="color:var(--ink)">Enterprise?</h3>
          <div class="mt-1 text-3xl md:text-4xl font-black price">Receive a Quote</div>
          <p class="mt-1 muted">Team-Building Package</p>
        </div>

        <ul class="space-y-3 mb-6 text-[15px]">
          <li class="flex items-start gap-3"><span class="check mt-0.5">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Unlimited Scraped Leads</li>
          <li class="flex items-start gap-3"><span class="check mt-0.5">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Exclusive Market Access</li>
          <li class="flex items-start gap-3"><span class="check mt-0.5">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Custom onboarding & ops build-out</li>
          <li class="flex items-start gap-3 muted"><span class="check mt-0.5 opacity-60">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></span>Dedicated success manager</li>
        </ul>

        <a href="{{ route('book') }}" class="btn btn-primary mt-auto w-full text-center">Schedule a Call</a>
      </div>
    </div>

    <!-- Value props -->
    <div class="mt-12 grid md:grid-cols-3 gap-6">
      <div class="rounded-xl plan-card p-5 flex items-start gap-3">
        <span class="check mt-0.5">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div>
          <div class="font-semibold" style="color:var(--ink)">Cancel anytime</div>
          <div class="text-sm muted">No long-term contracts. Upgrade or downgrade as you grow.</div>
        </div>
      </div>
      <div class="rounded-xl plan-card p-5 flex items-start gap-3">
        <span class="check mt-0.5">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div>
          <div class="font-semibold" style="color:var(--ink)">Real estate tuned</div>
          <div class="text-sm muted">Selectors, enrichment, and exports tailored for agents.</div>
        </div>
      </div>
      <div class="rounded-xl plan-card p-5 flex items-start gap-3">
        <span class="check mt-0.5">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div>
          <div class="font-semibold" style="color:var(--ink)">Data you can trust</div>
          <div class="text-sm muted">De-dupe & validation baked in. Clean lists = faster closes.</div>
        </div>
      </div>
    </div>

    <!-- CTA strip -->
    <div class="mt-12 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between plan-card">
      <div class="text-center md:text-left">
        <div class="text-xl md:text-2xl font-extrabold" style="color:var(--ink)">Not sure which plan is right?</div>
        <div class="muted">Walk through your pipeline goals with a specialist.</div>
      </div>
      <div class="mt-4 md:mt-0 flex items-center gap-3">
        <!-- <a href="{{ route('book') }}" class="btn btn-primary inline-flex items-center gap-2">
          Book a call
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
        </a> -->
        @guest
          <a href="{{ route('register') }}" class="btn btn-outline">Get a Tour</a>
        @else
          <a href="{{ route('book') }}" class="btn btn-primary">Contact sales</a>
        @endguest
      </div>
    </div>
  </div>
</section>
@endsection
