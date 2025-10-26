@extends('layouts.app')

@section('title', 'Dashboard — Agent Bookr')

@section('content')
@php
  /** @var \App\Models\User $user */

  $tierLabel = $user->getTierDisplayName();
  $isActive  = $user->hasActiveSubscription();

  // Recent runs & last downloadable CSV
  $recentRuns   = \App\Models\ScrapeRun::where('user_id', $user->id)->latest()->take(5)->get();
  $lastCsvRun   = \App\Models\ScrapeRun::where('user_id', $user->id)->where('status','succeeded')->latest()->first();
  $downloadHref = $lastCsvRun
      ? config('services.scraper.base').'/runs/'.$lastCsvRun->run_id.'/export.csv'
      : route('scrapes.history');

  // Small helper
  if (!function_exists('short_url')) {
      function short_url(?string $u): string {
          if (!$u) return '';
          $host = parse_url($u, PHP_URL_HOST) ?? '';
          $path = parse_url($u, PHP_URL_PATH) ?? '';
          $t = $host.$path;
          return strlen($t) > 58 ? substr($t,0,55).'…' : $t;
      }
  }
@endphp

<style>
  :root{ --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7; --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857; --ink:#0b1722; --muted:#6b7280; --border:#CAD2C5; }
  .dash-wrap{ background: radial-gradient(900px 420px at 6% -12%, rgba(16,185,129,.10), transparent 60%), radial-gradient(900px 420px at 94% -12%, rgba(5,150,105,.10), transparent 60%), #fff; min-height: 100vh; padding-top: 120px; }
  .grid-mask{ background-image: linear-gradient(to right, rgba(4,120,87,.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(4,120,87,.08) 1px, transparent 1px); background-size: 32px 32px; mask-image: radial-gradient(closest-side, #000, transparent 85%); pointer-events: none; }

  .card{ background: linear-gradient(180deg,#fff 0%, #f8fafc 100%); border: 1px solid var(--border); border-radius: 18px; box-shadow: 0 18px 60px rgba(16,185,129,.10); transition: transform .25s cubic-bezier(.2,.8,.2,1), box-shadow .25s cubic-bezier(.2,.8,.2,1), border-color .25s; }
  .card:hover{ transform: translateY(-4px); box-shadow: 0 22px 68px rgba(4,120,87,.16); border-color:#b7e4d8; }

  .btn{ display:inline-flex; align-items:center; justify-content:center; gap:.55rem; font-weight:800; border-radius:12px; padding:.9rem 1rem; transition: transform .2s, box-shadow .2s, filter .2s; }
  .btn svg{ width:1.05rem; height:1.05rem; }
  .btn-primary{ background: linear-gradient(135deg, var(--g500), var(--g600)); color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.22); }
  .btn-primary:hover{ filter: brightness(.98); transform: translateY(-1px); }
  .btn-ghost{ background:#fff; color: var(--g700); border:1px solid var(--border); }
  .btn-ghost:hover{ background: var(--g50); border-color: var(--g300); }

  .headline-chip{ background: var(--g100); color: var(--g700); }
  .ink{ color: var(--ink); }
  .muted{ color: var(--muted); }

  .acct-banner{ background: linear-gradient(180deg, #fff, var(--g50)); border:1px solid var(--g200); border-radius:16px; box-shadow: 0 10px 30px rgba(16,185,129,.10); }
  .status-dot{ width:.6rem; height:.6rem; border-radius:999px; display:inline-block; margin-right:.4rem; }
  .status-on{ background: var(--g500); box-shadow: 0 0 0 4px rgba(16,185,129,.15); }
  .status-off{ background: #f59e0b; box-shadow: 0 0 0 4px rgba(245,158,11,.15); }

  .stat{ display:flex; gap:14px; align-items:center; padding:1rem; border-radius:14px; background:#fff; border:1px solid var(--border); }
  .stat-icon{ display:flex; align-items:center; justify-content:center; width:42px; height:42px; border-radius:12px; background: linear-gradient(135deg, var(--g400), var(--g500)); color:#fff; box-shadow: 0 10px 24px rgba(16,185,129,.20); }

  .activity{ background:#f8fafc; border:1px solid var(--border); border-radius:14px; }
  .activity .icon{ width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; }
  .icon-green{ background: linear-gradient(135deg, var(--g500), var(--g600)); }
  .icon-mint{ background: linear-gradient(135deg, var(--g400), var(--g500)); }
  .icon-ink{ background: #0b1722; }

  .upgrade{ background: linear-gradient(120deg, var(--g500), var(--g600)); color:#fff; border-radius:18px; box-shadow:0 18px 60px rgba(16,185,129,.22); }
</style>

<section class="dash-wrap relative">
  <div class="absolute inset-0 grid-mask"></div>

  <div class="container mx-auto px-6 max-w-7xl relative z-10">
    <!-- Header -->
    <div class="mb-6 text-center">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold headline-chip">Welcome back</span>
      <h1 class="mt-3 text-3xl md:text-4xl font-extrabold ink">Welcome back, {{ $user->name }} </h1>
      <p class="mt-2 muted">Manage your lead generation and cold calling tools from one sleek hub.</p>
    </div>

    <!-- Account status -->
    <div class="acct-banner p-4 md:p-5 flex flex-col md:flex-row items-center justify-between mb-10">
      <div class="flex items-center gap-3 text-sm">
        <span class="uppercase tracking-widest text-xs muted">Current Subscription</span>
        <span class="uppercase text-xs text-black">{{ $tierLabel }} Plan</span>
        <span class="text-slate-400">•</span>
        @if($isActive)
          <span class="flex items-center font-semibold text-emerald-700"><i class="status-dot status-on"></i>Active subscription</span>
        @else
          <span class="flex items-center font-semibold text-amber-700"><i class="status-dot status-off"></i>Expired</span>
        @endif
      </div>
      <div class="mt-3 md:mt-0 flex gap-2">
        <!-- <a href="{{ route('book') }}" class="btn btn-ghost">Talk to sales</a> -->
        <a href="{{ route('scrapes.index') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 21l-4.35-4.35"/><circle cx="11" cy="11" r="8"/></svg>
          New scrape
        </a>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="card p-6">
        <div class="stat">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 13l3 3 7-7"/></svg>
          </div>
          <div>
            <p class="text-sm muted">Total Leads</p>
            <p class="text-2xl font-extrabold ink">
              @if($user->tier === 'user') 500 @elseif($user->tier === 'paid') 2,000 @else Unlimited @endif
            </p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="stat">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 11.19 19a19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.6 2.5"/></svg>
          </div>
          <div>
            <p class="text-sm muted">Scripts Available</p>
            <p class="text-2xl font-extrabold ink">
              @if($user->tier === 'user') Basic @elseif($user->tier === 'paid') Advanced @else All @endif
            </p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="stat">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20l9-5-9-5-9 5 9 5z"/><path d="M12 12l9-5-9-5-9 5 9 5z"/></svg>
          </div>
          <div>
            <p class="text-sm muted">Training Access</p>
            <p class="text-2xl font-extrabold ink">
              @if($user->tier === 'user') Limited @elseif($user->tier === 'paid') Full @else Complete @endif
            </p>
          </div>
        </div>
      </div>

      <div class="card p-6">
        <div class="stat">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10a6 6 0 0 1-6 6"/><circle cx="12" cy="10" r="3"/><path d="M5 20h14"/></svg>
          </div>
          <div>
            <p class="text-sm muted">Support Level</p>
            <p class="text-2xl font-extrabold ink">
              @if($user->tier === 'user') Priority @elseif($user->tier === 'paid') Priority @else 24/7 @endif
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 items-stretch auto-rows-fr">
  <!-- Lead Generation -->
  <div class="card p-6 h-full flex flex-col">
    <h3 class="text-xl font-extrabold ink mb-2">Lead Generation</h3>
    <p class="muted">Generate high-quality leads with our advanced targeting tools.</p>

    <div class="mt-auto space-y-3">
      <a href="{{ route('scrapes.index') }}" class="btn btn-primary w-full justify-center">Start Lead Generation</a>
      <a href="{{ route('scrapes.history') }}" class="btn btn-ghost w-full justify-center">View Recent Runs</a>
    </div>
  </div>

  <!-- Cold Calling Tools -->
  <div class="card p-6 h-full flex flex-col">
    <h3 class="text-xl font-extrabold ink mb-2">Cold Calling Tools</h3>
    <p class="muted">Access proven scripts and training materials to improve your success rate.</p>

    <div class="mt-auto space-y-3">
      <a href="{{ route('resources.scripts') }}" class="btn btn-primary w-full justify-center">View Scripts</a>
      <a href="{{ route('resources.tutorials') }}" class="btn btn-ghost w-full justify-center">Watch Tutorials</a>
    </div>
  </div>
</div>

    <!-- Recent Activity (driven by ScrapeRun) -->
    <div class="card p-6">
      <h3 class="text-xl font-extrabold ink mb-4">Recent Activity</h3>

      @if($recentRuns->isEmpty())
        <div class="p-4 text-center text-sm muted">No recent activity yet. Start a new scrape to see progress here.</div>
      @else
        <div class="space-y-4">
          @foreach($recentRuns as $run)
            @php
              $ok     = $run->status === 'succeeded';
              $warn   = $run->status === 'failed';
              $icon   = $ok ? 'icon-green' : ($warn ? 'icon-ink' : 'icon-mint');
              $title  = $ok ? 'Lead Generation Completed' : ($warn ? 'Lead Generation Failed' : 'Lead Generation Started');
              $detail = $ok
                        ? (($run->count ?? 0).' leads • '.short_url($run->base_url))
                        : short_url($run->base_url);
              $when   = optional($run->updated_at ?? $run->created_at)->diffForHumans();
            @endphp
            <div class="activity p-4 flex items-center">
              <div class="icon {{ $icon }} mr-4">
                @if($ok)
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7l-8 10-5-5"/></svg>
                @elseif($warn)
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                @else
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                @endif
              </div>
              <div class="flex-1">
                <p class="font-semibold ink">{{ $title }}</p>
                <p class="text-sm muted">{{ $detail }}</p>
              </div>
              <span class="text-sm text-slate-500">{{ $when }}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Upgrade CTA -->
    @if($user->tier === 'user')
      <div class="upgrade p-8 my-8 text-center">
        <h3 class="text-2xl font-extrabold mb-2">Ready to scale your business?</h3>
        <p class="text-white/90 text-lg mb-6">Upgrade to Professional and get more leads, advanced scripts, and priority support.</p>
        <a href="{{ route('book') }}" class="btn btn-ghost" style="background:#fff; color:var(--ink);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
          Talk to Sales
        </a>
      </div>
    @endif
  </div>
</section>
@endsection
