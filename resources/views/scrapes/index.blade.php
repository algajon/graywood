@extends('layouts.app')

@section('title', 'Lead Generation — Agent Bookr')

@section('content')
@php
  /** @var \App\Models\User|null $user */
  $authed = auth()->check();
  $user   = auth()->user();

  // Normalize tier to one of: guest | user | paid | admin
  $tierRaw = $authed ? strtolower((string)($user->tier ?? 'user')) : 'guest';
  $tier = match (true) {
    // trust explicit admin method if present
    $user && method_exists($user, 'isAdmin') && $user->isAdmin() => 'admin',
    in_array($tierRaw, ['admin','administrator','root','superadmin']) => 'admin',
    in_array($tierRaw, ['paid','pro','professional'])               => 'paid',
    in_array($tierRaw, ['user','starter','free'])                    => 'user',
    default                                                          => 'guest',
  };

  // Label (keeps your existing display names)
  $tierLabel = $authed ? ($user?->getTierDisplayName() ?? 'Starter') : 'Starter';

  // Weekly caps
  $caps = [
    'guest' => 25,   // Starter (guest)
    'user'  => 25,   // Starter (logged-in free)
    'paid'  => 50,   // Professional
    'admin' => null, // Unlimited
  ];
  $weekCap = $caps[$tier]; // no fallback—tier is normalized above

  // Default input value (50 for unlimited, otherwise up to 50)
  $defaultListings = is_null($weekCap) ? 50 : min(50, $weekCap);
@endphp

<style>
  :root{
    --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7;
    --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857;
    --ink:#0b1722; --muted:#6b7280; --border:#CAD2C5;
  }
  .lg-wrap{
    background:
      radial-gradient(900px 420px at 6% -12%, rgba(16,185,129,.10), transparent 60%),
      radial-gradient(900px 420px at 94% -12%, rgba(5,150,105,.10), transparent 60%),
      #fff;
    min-height: 100vh; padding-top: 120px; /* leaves room for fixed header */
  }
  .grid-mask{
    background-image:
      linear-gradient(to right, rgba(4,120,87,.08) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(4,120,87,.08) 1px, transparent 1px);
    background-size: 32px 32px; mask-image: radial-gradient(closest-side, #000, transparent 85%); pointer-events:none;
  }
  .card{
    background: linear-gradient(180deg,#fff 0%, #f8fafc 100%);
    border: 1px solid var(--border); border-radius: 20px;
    box-shadow: 0 18px 60px rgba(16,185,129,.10);
    transition: transform .25s cubic-bezier(.2,.8,.2,1), box-shadow .25s cubic-bezier(.2,.8,.2,1), border-color .25s;
  }
  .card:hover{ transform: translateY(-4px); box-shadow: 0 22px 68px rgba(4,120,87,.16); border-color:#b7e4d8; }

  .ink{ color: var(--ink); } .muted{ color: var(--muted); }
  .headline-chip{ background: var(--g100); color: var(--g700); }

  .field{ width:100%; padding:.85rem 1rem; border:1px solid #E5E7EB; border-radius:12px; color:#111827; transition: box-shadow .2s, border-color .2s; }
  .field:focus{ outline:none; border-color: var(--g300); box-shadow: 0 0 0 3px rgba(16,185,129,.20); background:#fff; }
  .field-wrap{ position:relative; }
  .field-icon{ position:absolute; left:12px; top:50%; transform:translateY(-50%); pointer-events:none; color:#059669; }
  .pad-icon{ padding-left: 2.3rem; }

  .btn{ display:inline-flex; align-items:center; justify-content:center; gap:.55rem; font-weight:800; border-radius:14px; padding:1rem 1.15rem; transition: transform .2s, box-shadow .2s, filter .2s; }
  .btn svg{ width:1.05rem; height:1.05rem; }
  .btn-primary{ background: linear-gradient(135deg, var(--g500), var(--g600)); color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.22); }
  .btn-primary:hover{ filter:brightness(.98); transform:translateY(-1px); }
  .btn-ghost{ background:#fff; color: var(--g700); border:1px solid var(--border); }
  .btn-ghost:hover{ background: var(--g50); border-color: var(--g300); }

  .info{ background: linear-gradient(180deg, #fff, var(--g50)); border:1px solid var(--g200); border-radius:14px; }
  .cap-chip{ background: var(--g100); color: var(--g700); border:1px solid var(--g200); padding:.25rem .5rem; border-radius:999px; font-size:.75rem; font-weight:700; }
  .cap-bar{ height:6px; background:#eef2f7; border-radius:999px; overflow:hidden; }
  .cap-fill{ height:100%; background: linear-gradient(90deg, var(--g400), var(--g600)); width:0%; transition: width .2s ease; }

  /* IMPORTANT: do NOT override Tailwind's .hidden */
  .cap-hidden{ display:none; }
</style>

<section class="lg-wrap relative">
  <div class="absolute inset-0 grid-mask"></div>

  <div class="container mx-auto px-6 max-w-5xl relative z-10">
    <!-- Header -->
    <div class="text-center mb-8">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold headline-chip">Lead Generation</span>
      <h1 class="mt-3 text-3xl md:text-4xl font-extrabold ink">Lead Generation Tool</h1>
      <p class="mt-2 text-lg muted max-w-2xl mx-auto">
        Generate high-quality real estate leads with our advanced scraping technology. Start building your prospect list today.
      </p>
    </div>

    <!-- Account plan chip -->
    <div class="info mb-8 p-4 flex items-center justify-between">
      <div class="flex items-center gap-2 text-sm">
        <svg class="text-emerald-600" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>
        <span class="font-semibold ink">Your Plan:</span>
        <span class="font-semibold text-emerald-700">{{ $tierLabel }}</span>
        <span class="ml-3 cap-chip">
          Weekly cap:
          @if(is_null($weekCap)) Unlimited @else {{ number_format($weekCap) }} listings @endif
        </span>
      </div>
@if($authed)
  @if($tier === 'user') 
    <a href="{{ route('book') }}" class="btn btn-ghost">Upgrade options</a>
  @endif
@else
  <a href="{{ route('register') }}" class="btn btn-ghost">Create account</a>
@endif
    </div>

    <!-- Form card -->
    <div class="card overflow-hidden">
      <div class="px-8 py-6 text-center">
        <h2 class="text-2xl font-extrabold ink mb-1">Start Lead Generation</h2>
        <p class="muted">Configure your search parameters</p>
      </div>

      <div class="px-8 pb-8">
        <form method="POST" action="{{ route('scrapes.start') }}" class="space-y-6" onsubmit="return window.AB_capCheck?.()">
          @csrf

          <!-- Search URL -->
          <div>
            <label for="base_url" class="block text-sm font-semibold ink mb-2">Search URL *</label>
            <div class="field-wrap">
              <span class="field-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 1 7 7l-1 1a5 5 0 0 1-7-7l1-1"/><path d="M14 11a5 5 0 0 1 7 7l-1 1a5 5 0 0 1-7-7l1-1"/></svg>
              </span>
              <input
                id="base_url"
                name="base_url"
                type="url"
                required
                class="field pad-icon"
                placeholder="https://www.kijiji.ca/b-real-estate/..."
              />
            </div>
            <p class="mt-2 text-sm muted">Enter the Kijiji search URL for the area and property type you want to target.</p>
          </div>

          <!-- Max listings (plan-aware) -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label for="max_listings" class="block text-sm font-semibold ink">Maximum Listings (this run)</label>
              <span class="cap-chip" id="capLabel">
                @if(is_null($weekCap)) Unlimited per run @else Up to {{ number_format($weekCap) }} @endif
              </span>
            </div>
            <div class="field-wrap">
              <span class="field-icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/></svg>
              </span>
              <input
                id="max_listings"
                name="max_listings"
                type="number"
                min="1"
                @if(!is_null($weekCap)) max="{{ $weekCap }}" @endif
                value="{{ $defaultListings }}"
                class="field pad-icon"
                inputmode="numeric"
              />
            </div>

            <!-- was "hidden" (conflicted with Tailwind). Now "cap-hidden" -->
            <div class="mt-2 cap-bar @if(is_null($weekCap)) cap-hidden @endif" aria-hidden="true">
              <div class="cap-fill" id="capFill"></div>
            </div>

<p class="mt-2 text-sm muted">
  @switch($tier)
    @case('guest')
    @case('user')
      Starter plan: up to 25 listings per week. Individual runs cannot exceed this cap.
      @break
    @case('paid')
      Professional plan: up to 50 listings per week. Individual runs cannot exceed this cap.
      @break
    @default
      Administrator plan: unlimited listings per week.
  @endswitch
</p>
          </div>

          <button type="submit" class="btn btn-primary w-full justify-center text-lg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 21l-4.35-4.35"/><circle cx="11" cy="11" r="8"/></svg>
            Start Lead Generation
          </button>
        </form>
      </div>
    </div>

    <!-- Tips -->
    <div class="my-8 card p-6">
      <h3 class="text-xl font-extrabold ink mb-4">Pro Tips for Better Results</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="flex gap-3">
          <div style="flex:0 0 auto; width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; background: linear-gradient(135deg, var(--g500), var(--g600)); box-shadow: 0 8px 20px rgba(16,185,129,.22);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-7 7c0 2.8 1.5 4.5 3 6h8c1.5-1.5 3-3.2 3-6a7 7 0 0 0-7-7z"/></svg>
          </div>
          <div>
            <h4 class="font-semibold ink">Target specific areas</h4>
            <p class="text-sm muted">Focus on neighborhoods with high property turnover for better lead quality.</p>
          </div>
        </div>

        <div class="flex gap-3">
          <div style="flex:0 0 auto; width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; background: linear-gradient(135deg, var(--g500), var(--g600)); box-shadow: 0 8px 20px rgba(16,185,129,.22);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          </div>
          <div>
            <h4 class="font-semibold ink">Optimal timing</h4>
            <p class="text-sm muted">Run searches during business hours for the most up-to-date listings.</p>
          </div>
        </div>

        <div class="flex gap-3">
          <div style="flex:0 0 auto; width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; background: linear-gradient(135deg, var(--g500), var(--g600)); box-shadow: 0 8px 20px rgba(16,185,129,.22);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18"/><path d="M3 12h18"/><path d="M17 3v18"/><path d="M7 3v18"/></svg>
          </div>
          <div>
            <h4 class="font-semibold ink">Use filters</h4>
            <p class="text-sm muted">Apply price and property type filters to target your ideal prospects.</p>
          </div>
        </div>

        <div class="flex gap-3">
          <div style="flex:0 0 auto; width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; background: linear-gradient(135deg, var(--g500), var(--g600)); box-shadow: 0 8px 20px rgba(16,185,129,.22);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
          </div>
          <div>
            <h4 class="font-semibold ink">Export results</h4>
            <p class="text-sm muted">Download your leads as CSV for easy import into your CRM system.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  (function(){
    const cap = {{ is_null($weekCap) ? 'null' : (int) $weekCap }};
    const input = document.getElementById('max_listings');
    const fill  = document.getElementById('capFill');

    function clampAndPaint(){
      if (!input) return;
      let v = parseInt(input.value || '0', 10);
      if (v < 1) v = 1;
      if (cap !== null && v > cap) v = cap;
      input.value = v;

      if (cap !== null && fill){
        const pct = Math.min(100, Math.max(0, (v / cap) * 100));
        fill.style.width = pct + '%';
      }
    }

    if (input){
      input.addEventListener('input', clampAndPaint, { passive: true });
      clampAndPaint();
    }

    // Guard on submit as well
    window.AB_capCheck = function(){
      if (!input) return true;
      const v = parseInt(input.value || '0', 10);
      if (cap !== null && v > cap){
        input.value = cap;
        clampAndPaint();
      }
      return true;
    };
  })();
</script>
@endsection
