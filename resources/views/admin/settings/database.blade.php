@extends('layouts.app')

@section('title', 'Admin — Settings (Database)')

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
      <h1 class="text-3xl font-extrabold ink mb-1">Database</h1>
      <p class="muted mb-6">Connection info (read-only).</p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="shell p-5">
          <div class="text-sm muted">Default Connection</div>
          <div class="text-lg font-bold ink">{{ $default }}</div>
        </div>
        <div class="shell p-5 md:col-span-2">
          <div class="text-sm muted mb-2">Connection Details</div>
          <pre class="text-xs">{{ json_encode($connection, JSON_PRETTY_PRINT) }}</pre>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
