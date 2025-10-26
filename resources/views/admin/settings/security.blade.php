@extends('layouts.app')

@section('title', 'Admin — Settings (Security)')

@section('content')
<section class="admin-wrap relative min-h-[100svh] pt-[120px] pb-16">
  <style>
    :root{ --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7; --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857; --ink:#0b1722; --muted:#6b7280; --border:#CAD2C5; }
    .admin-wrap{ background:
      radial-gradient(900px 420px at 6% -12%, rgba(16,185,129,.10), transparent 60%),
      radial-gradient(900px 420px at 94% -12%, rgba(5,150,105,.10), transparent 60%),
      #fff; }
    .shell{ background:linear-gradient(180deg,#fff 0%,#f8fafc 100%); border:1px solid var(--border); border-radius:18px; box-shadow:0 18px 60px rgba(16,185,129,.10); }
    .ink{ color:var(--ink); } .muted{ color:var(--muted); }
  </style>

  <div class="container mx-auto px-6 max-w-4xl">
    <div class="shell p-6 md:p-7">
      <h1 class="text-3xl font-extrabold ink mb-1">Security Settings</h1>
      <p class="muted mb-6">Sessions & password settings.</p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="shell p-5">
          <div class="text-sm muted">Session Driver</div>
          <div class="text-lg font-bold ink">{{ $sessionDriver }}</div>
        </div>
        <div class="shell p-5">
          <div class="text-sm muted">Session Lifetime</div>
          <div class="text-lg font-bold ink">{{ $sessionLifetime }} minutes</div>
        </div>
        <div class="shell p-5">
          <div class="text-sm muted">Cipher</div>
          <div class="text-lg font-bold ink">{{ $cipher }}</div>
        </div>
        <div class="shell p-5">
          <div class="text-sm muted">App Key</div>
          <div class="text-lg font-bold ink">{{ $appKeySet ? 'Set' : 'Missing' }}</div>
        </div>
      </div>

      <div class="shell p-5 mt-6">
        <div class="text-sm muted mb-1">Password Broker (users)</div>
        <pre class="text-xs">{{ json_encode($passwords, JSON_PRETTY_PRINT) }}</pre>
      </div>
    </div>
  </div>
</section>
@endsection
