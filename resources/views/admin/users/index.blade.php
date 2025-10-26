@extends('layouts.app')

@section('title', 'Admin — Users')

@section('content')
<section class="admin-wrap relative min-h-[100svh] pt-[120px] pb-16">
  <style>
    :root{ --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7; --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857; --ink:#0b1722; --muted:#6b7280; --border:#CAD2C5; }
    .admin-wrap{ background:
      radial-gradient(900px 420px at 6% -12%, rgba(16,185,129,.10), transparent 60%),
      radial-gradient(900px 420px at 94% -12%, rgba(5,150,105,.10), transparent 60%),
      #fff; }
    .shell{ background:linear-gradient(180deg,#fff 0%,#f8fafc 100%); border:1px solid var(--border); border-radius:18px; box-shadow:0 18px 60px rgba(16,185,129,.10); transition:.25s; }
    .shell:hover{ transform:translateY(-3px); box-shadow:0 22px 68px rgba(4,120,87,.16); border-color:#b7e4d8; }
    .ink{ color:var(--ink); } .muted{ color:var(--muted); }
    .btn{ display:inline-flex; align-items:center; gap:.5rem; font-weight:800; border-radius:12px; padding:.8rem 1rem; transition:.2s; }
    .btn-primary{ background:linear-gradient(135deg,var(--g500),var(--g600)); color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.22); }
    .btn-primary:hover{ filter:brightness(.98); transform:translateY(-1px); }
    .btn-ghost{ background:#fff; color:var(--g700); border:1px solid var(--border); }
    .btn-ghost:hover{ background:var(--g50); border-color:var(--g300); }
    .icon-btn{ display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; border:1px solid var(--border); background:#fff; }
    .icon-btn:hover{ transform:translateY(-1px); border-color:var(--g300); background:var(--g50); }
    table.adm{ width:100%; }
    table.adm thead th{ font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; font-weight:800; color:#0b1722; background:#f8fafc; border-bottom:1px solid var(--border); padding:.9rem 1rem; text-align:left; }
    table.adm tbody td{ padding:1rem; border-bottom:1px solid #eef2f1; color:#1f2937; vertical-align:middle; }
    table.adm tbody tr:hover{ background:#f9fffc; }
    .chip{ display:inline-flex; align-items:center; gap:.45rem; padding:.35rem .6rem; border-radius:999px; font-size:.72rem; font-weight:800; }
    .chip-active{ background:#dcfce7; color:#065f46; border:1px solid #86efac; }
    .chip-starter{ background:var(--g100); color:var(--g700); border:1px solid var(--g200); }
  </style>

  <div class="container mx-auto px-6 max-w-7xl">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-3xl md:text-4xl font-extrabold ink">Users</h1>
        <p class="muted mt-1">Search, filter, create, edit, or export users.</p>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.users.export') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
          Export CSV
        </a>
        <form method="post" action="{{ route('admin.users.store') }}">
          @csrf
          <button class="btn btn-ghost">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Quick Add
          </button>
        </form>
      </div>
    </div>

    <!-- Filters -->
    <div class="shell p-5 mb-6">
      <form method="get" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or email…" class="w-full border rounded-md px-3 py-2" />
        <select name="tier" class="w-full border rounded-md px-3 py-2">
          <option value="">All tiers</option>
          <option value="user" @selected(request('tier')==='user')>Starter</option>
          <option value="paid" @selected(request('tier')==='paid')>Professional</option>
          <option value="admin" @selected(request('tier')==='admin')>Administrator</option>
        </select>
        <div class="flex gap-2">
          <button class="btn btn-ghost">Apply</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Reset</a>
        </div>
      </form>
    </div>

    <!-- Table -->
    <div class="shell overflow-x-auto">
      <table class="adm">
        <thead>
          <tr>
            <th>User</th>
            <th>Tier</th>
            <th>Status</th>
            <th>Joined</th>
            <th class="w-40">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white">
          @forelse($users as $user)
          <tr>
            <td>
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-full flex items-center justify-center mr-3"
                     style="background:linear-gradient(135deg,var(--g500),var(--g600)); box-shadow:0 8px 20px rgba(16,185,129,.22);">
                  <span class="text-white font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                  <div class="text-sm font-semibold ink">{{ $user->name }}</div>
                  <div class="text-sm muted">{{ $user->email }}</div>
                </div>
              </div>
            </td>
            <td>
              @php $label = $user->getTierDisplayName(); @endphp
              <span class="chip {{ $user->tier === 'paid' ? 'chip-active' : 'chip-starter' }}">{{ $label }}</span>
            </td>
            <td>
              @if(method_exists($user,'hasActiveSubscription') && $user->hasActiveSubscription())
                <span class="chip chip-active">Active</span>
              @else
                <span class="chip" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca">Inactive</span>
              @endif
            </td>
            <td class="text-sm muted">{{ $user->created_at->format('M d, Y') }}</td>
            <td>
              <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.show', $user) }}" class="icon-btn" title="View">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
                <button class="icon-btn js-edit" data-id="{{ $user->id }}" title="Edit">
                  <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </button>
                <form method="post" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                  @csrf @method('DELETE')
                  <button class="icon-btn" title="Delete">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="p-6 text-center muted">No users found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $users->withQueryString()->links() }}
    </div>
  </div>

  <!-- Simple inline edit modal (ajax to admin.users.update) -->
  <dialog id="editUserModal" class="w-full max-w-md rounded-xl p-0 border border-[var(--border)]">
    <form method="dialog" class="shell p-5">
      <h3 class="text-lg font-extrabold ink mb-3">Edit User</h3>
      <input type="hidden" id="editId">
      <div class="grid gap-3">
        <input id="editName" class="border rounded-md px-3 py-2" placeholder="Name">
        <input id="editEmail" class="border rounded-md px-3 py-2" placeholder="Email">
        <select id="editTier" class="border rounded-md px-3 py-2">
          <option value="user">Starter</option>
          <option value="paid">Professional</option>
          <option value="admin">Administrator</option>
        </select>
      </div>
      <div class="mt-4 flex justify-end gap-2">
        <button class="btn btn-ghost" value="cancel">Cancel</button>
        <button class="btn btn-primary" id="saveEdit" type="button">Save</button>
      </div>
    </form>
  </dialog>

  <script>
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('.js-edit');
      if (!btn) return;
      const id = btn.dataset.id;
      const res = await fetch(`{{ url('/admin/users') }}/${id}`);
      const u = await res.json();
      editId.value = u.id;
      editName.value = u.name;
      editEmail.value = u.email;
      editTier.value = u.tier;
      editUserModal.showModal();
    });

    saveEdit?.addEventListener('click', async () => {
      const id = editId.value;
      const payload = {
        name: editName.value,
        email: editEmail.value,
        tier: editTier.value,
        _method: 'PUT',
        _token: '{{ csrf_token() }}'
      };
      const res = await fetch(`{{ url('/admin/users') }}/${id}`, {
        method:'POST',
        headers:{ 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest', 'Content-Type':'application/json' },
        body: JSON.stringify(payload)
      });
      if (res.ok) location.reload();
    });
  </script>
</section>
@endsection
