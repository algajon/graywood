@extends('layouts.app')

@section('title', 'Admin — Analytics')

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
    .stat{ display:flex; align-items:center; gap:.9rem; }
    .stat .icon{ width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; background: linear-gradient(135deg, var(--g500), var(--g600)); box-shadow:0 10px 24px rgba(16,185,129,.22); }
  </style>

  <div class="container mx-auto px-6 max-w-6xl">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="shell p-6 stat">
        <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/></svg></div>
        <div><div class="muted text-sm">Total Users</div><div class="text-2xl font-extrabold ink">{{ $total }}</div></div>
      </div>
      <div class="shell p-6 stat">
        <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 21h8"/><path d="M12 17v4"/><path d="M7 8h10v6H7z"/><path d="M7 4h10v4H7z"/></svg></div>
        <div><div class="muted text-sm">Paid Users</div><div class="text-2xl font-extrabold ink">{{ $paid }}</div></div>
      </div>
      <div class="shell p-6 stat">
        <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/></svg></div>
        <div><div class="muted text-sm">Free Users</div><div class="text-2xl font-extrabold ink">{{ $free }}</div></div>
      </div>
      <div class="shell p-6 stat">
        <div class="icon"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg></div>
        <div><div class="muted text-sm">MRR</div><div class="text-2xl font-extrabold ink">${{ number_format($mrr) }}/mo</div></div>
      </div>
    </div>

    <div class="shell p-6 md:p-7">
      <h2 class="text-xl md:text-2xl font-extrabold ink mb-4">Signups (Last 30 days)</h2>
      <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
        <table class="w-full">
          <thead>
            <tr>
              <th class="text-left text-xs uppercase tracking-wider font-bold p-3 border-b">Date</th>
              <th class="text-left text-xs uppercase tracking-wider font-bold p-3 border-b">New Users</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @forelse($signups as $row)
              <tr class="hover:bg-[#f9fffc]">
                <td class="p-3">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                <td class="p-3">{{ $row->count }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="p-6 text-center muted">No data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
