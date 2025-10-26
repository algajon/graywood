@extends('layouts.app')

@section('title', 'Admin — Notifications')

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

  <div class="container mx-auto px-6 max-w-5xl">
    <div class="shell p-6 md:p-7">
      <h1 class="text-3xl font-extrabold ink mb-1">Notifications</h1>

      <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
        <table class="w-full">
          <thead>
            <tr>
              <th class="text-left text-xs uppercase tracking-wider font-bold p-3 border-b">ID</th>
              <th class="text-left text-xs uppercase tracking-wider font-bold p-3 border-b">Type</th>
              <th class="text-left text-xs uppercase tracking-wider font-bold p-3 border-b">Notifiable</th>
              <th class="text-left text-xs uppercase tracking-wider font-bold p-3 border-b">Data</th>
              <th class="text-left text-xs uppercase tracking-wider font-bold p-3 border-b">Created</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @forelse($notifications as $n)
              <tr class="hover:bg-[#f9fffc]">
                <td class="p-3">{{ $n->id }}</td>
                <td class="p-3">{{ $n->type }}</td>
                <td class="p-3">{{ $n->notifiable_type }} #{{ $n->notifiable_id }}</td>
                <td class="p-3"><pre class="text-xs">{{ $n->data }}</pre></td>
                <td class="p-3">{{ \Carbon\Carbon::parse($n->created_at)->format('M d, Y H:i') }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="p-6 text-center muted">No notifications available.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
