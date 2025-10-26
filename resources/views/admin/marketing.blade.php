@extends('layouts.app')

@section('title', 'Marketing Settings — Admin')

@section('content')
@php
  // Handy accessors
  $val = fn ($path, $fallback = '') => old(str_replace(['[',']'], ['.',''], $path), data_get($settings, str_replace(['[',']'], ['.',''], $path), $fallback));
@endphp

<style>
  :root{
    --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7;
    --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857;
    --ink:#0b1722; --muted:#6b7280; --border:#CAD2C5;
  }
  .admin-wrap{
    background:
      radial-gradient(900px 420px at 6% -12%, rgba(16,185,129,.10), transparent 60%),
      radial-gradient(900px 420px at 94% -12%, rgba(5,150,105,.10), transparent 60%),
      #fff;
    min-height:100vh; padding-top:120px; position:relative;
  }
  .grid-mask{
    position:absolute; inset:0; pointer-events:none;
    background-image:
      linear-gradient(to right, rgba(4,120,87,.08) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(4,120,87,.08) 1px, transparent 1px);
    background-size:32px 32px;
    mask-image: radial-gradient(closest-side, #000, transparent 85%);
  }
  .card{
    background:linear-gradient(180deg,#fff,#f8fafc);
    border:1px solid var(--border);
    border-radius:18px;
    box-shadow:0 18px 60px rgba(16,185,129,.10);
    transition:transform .22s cubic-bezier(.2,.8,.2,1), box-shadow .22s, border-color .22s;
  }
  .card:hover{ transform:translateY(-2px); box-shadow:0 22px 68px rgba(4,120,87,.16); border-color:#b7e4d8; }
  .btn{ display:inline-flex; align-items:center; gap:.55rem; font-weight:800; border-radius:12px; padding:.85rem 1rem; }
  .btn svg{ width:1.05rem; height:1.05rem; }
  .btn-primary{ background:linear-gradient(135deg,var(--g500),var(--g600)); color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.22); }
  .btn-primary:hover{ filter:brightness(.98); transform:translateY(-1px); color:#fff; }
  .btn-ghost{ background:#fff; color:var(--g700); border:1px solid var(--border); }
  .btn-ghost:hover{ background:var(--g50); border-color:var(--g300); }
  .ink{ color:var(--ink) } .muted{ color:var(--muted) }
  .headline-chip{ background:var(--g100); color:var(--g700); }
  .section-h{ display:flex; align-items:center; justify-content:space-between; gap:.75rem; }
  .section-h h2{ font-weight:900; color:var(--ink); }
  .input{ width:100%; border:1px solid var(--border); background:#fff; border-radius:12px; padding:.75rem .9rem; }
  .input:focus{ outline:none; box-shadow:0 0 0 4px rgba(16,185,129,.15); border-color:var(--g300); }
  .help{ font-size:.82rem; color:#64748b; }
  .badge{ display:inline-flex; align-items:center; gap:.4rem; font-weight:700; font-size:.72rem; padding:.25rem .6rem; border-radius:999px; background:var(--g100); color:var(--g700); }
  .flash-ok{ background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; border-radius:12px; }
  .flash-err{ background:#fff7ed; border:1px solid #fed7aa; color:#9a3412; border-radius:12px; }
</style>

<section class="admin-wrap">
  <div class="grid-mask"></div>

  <div class="container mx-auto px-6 max-w-5xl relative z-10">
    <!-- Page header -->
    <div class="mb-8">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold headline-chip">Admin</span>
      <h1 class="mt-3 text-3xl md:text-4xl font-extrabold ink">Marketing Settings</h1>
      <p class="muted mt-1">Control headlines, CTAs, pricing blurbs, and FAQs used across the site.</p>
    </div>

    <!-- Flash & errors -->
    @if (session('status'))
      <div class="flash-ok p-4 mb-6">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
      <div class="flash-err p-4 mb-6">
        <div class="font-semibold mb-1">Please fix the following:</div>
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.marketing.update') }}" class="space-y-8">
      @csrf

      <!-- Hero -->
      <div class="card p-6">
        <div class="section-h mb-4">
          <h2 class="text-xl">Hero Section</h2>
          <span class="badge">Above the fold</span>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold mb-1 ink">Primary Headline (H1)</label>
            <input class="input" type="text" name="headlines[hero_h1]"
                   value="{{ $val('headlines[hero_h1]', 'Generate More Leads') }}"
                   placeholder="Generate More Leads">
            <p class="help mt-1">Main promise on the homepage hero.</p>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1 ink">Secondary Headline (H2)</label>
            <input class="input" type="text" name="headlines[hero_h2]"
                   value="{{ $val('headlines[hero_h2]', 'Close More Deals') }}"
                   placeholder="Close More Deals">
            <p class="help mt-1">Short subheading that supports your H1.</p>
          </div>
        </div>
        <div class="mt-6">
          <label class="block text-sm font-semibold mb-1 ink">Hero CTA Button Text</label>
          <input class="input" type="text" name="cta[hero]"
                 value="{{ $val('cta[hero]', 'Book a Call Now') }}"
                 placeholder="Book a Call Now">
        </div>
      </div>

      <!-- Navigation -->
      <div class="card p-6">
        <div class="section-h mb-4">
          <h2 class="text-xl">Navigation</h2>
          <span class="badge">Header & Footer</span>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold mb-1 ink">Navbar CTA Text</label>
            <input class="input" type="text" name="cta[navbar]"
                   value="{{ $val('cta[navbar]', 'Get Started') }}"
                   placeholder="Get Started">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1 ink">Footer CTA Text</label>
            <input class="input" type="text" name="cta[footer]"
                   value="{{ $val('cta[footer]', 'Book Now') }}"
                   placeholder="Book Now">
          </div>
        </div>
      </div>

      <!-- Pricing -->
      <div class="card p-6">
        <div class="section-h mb-4">
          <h2 class="text-xl">Pricing Section</h2>
          <span class="badge">Plans</span>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1 ink">Pricing Section Blurb</label>
          <input class="input" type="text" name="pricing[blurb]"
                 value="{{ $val('pricing[blurb]', 'Display-only plans. Book a call to get started.') }}"
                 placeholder="Short description that appears above the plans">
        </div>
        <div class="grid md:grid-cols-3 gap-6 mt-6">
          <div>
            <label class="block text-sm font-semibold mb-1 ink">Part-Time Plan CTA</label>
            <input class="input" type="text" name="cta[pricing_part_time]"
                   value="{{ $val('cta[pricing_part_time]') }}" placeholder="Choose Part-Time">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1 ink">Full-Time Plan CTA</label>
            <input class="input" type="text" name="cta[pricing_full_time]"
                   value="{{ $val('cta[pricing_full_time]') }}" placeholder="Choose Full-Time">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1 ink">Enterprise Plan CTA</label>
            <input class="input" type="text" name="cta[pricing_enterprise]"
                   value="{{ $val('cta[pricing_enterprise]') }}" placeholder="Contact Sales">
          </div>
        </div>
      </div>

      <!-- Tenant -->
      <div class="card p-6">
        <div class="section-h mb-4">
          <h2 class="text-xl">Tenant Section</h2>
          <span class="badge">Optional</span>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1 ink">Tenant CTA Text</label>
          <input class="input" type="text" name="cta[tenant]"
                 value="{{ $val('cta[tenant]') }}" placeholder="Apply Now">
        </div>
      </div>

      <!-- FAQs -->
      <div class="card p-6">
        <div class="section-h mb-4">
          <h2 class="text-xl">FAQs</h2>
          <span class="badge">Support</span>
        </div>
        <label class="block text-sm font-semibold mb-1 ink">FAQs JSON (array of {"question","answer"})</label>
        <textarea name="faqs_json" rows="10" class="input font-mono text-sm"
                  placeholder='[{"question":"What is…?","answer":"It is…"}]'>{{ old('faqs_json', $faqsJson) }}</textarea>
        <p class="help mt-2">Tip: paste valid JSON. Each item should include <code>question</code> and <code>answer</code>.</p>
      </div>

      <!-- Actions -->
      <div class="flex items-center gap-3 pt-2 pb-10">
        <button type="submit" class="btn btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V7"/><path d="M7 3h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M3 7h18"/>
          </svg>
          Save All Settings
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 18l-6-6 6-6"/>
          </svg>
          Back to Admin
        </a>
      </div>
    </form>
  </div>
</section>
@endsection
