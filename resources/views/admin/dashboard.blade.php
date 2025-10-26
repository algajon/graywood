@extends('layouts.app')

@section('title', 'Admin Dashboard — Agent Bookr')

@section('content')
<section class="admin-wrap relative min-h-[100svh] pt-[120px] pb-16">
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
    }
    .grid-mask{
      position:absolute; inset:0;
      background-image:
        linear-gradient(to right, rgba(4,120,87,.08) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(4,120,87,.08) 1px, transparent 1px);
      background-size:32px 32px;
      mask-image: radial-gradient(closest-side, #000, transparent 86%);
      pointer-events:none;
    }

    .shell{
      background: linear-gradient(180deg,#fff 0%, #f8fafc 100%);
      border:1px solid var(--border);
      border-radius: 18px;
      box-shadow: 0 18px 60px rgba(16,185,129,.10);
      transition: transform .25s cubic-bezier(.2,.8,.2,1), box-shadow .25s cubic-bezier(.2,.8,.2,1), border-color .25s;
    }
    .shell:hover{ transform: translateY(-3px); box-shadow: 0 22px 68px rgba(4,120,87,.16); border-color:#b7e4d8; }

    .ink{ color: var(--ink); }
    .muted{ color: var(--muted); }

    .chip{ display:inline-flex; align-items:center; gap:.45rem; padding:.4rem .65rem; border-radius:999px; font-size:.72rem; font-weight:800; }
    .chip-admin{ background: var(--g100); color: var(--g700); border:1px solid var(--g200); }
    .chip-active{ background:#dcfce7; color:#065f46; border:1px solid #86efac; }
    .chip-inactive{ background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

    .btn{ display:inline-flex; align-items:center; justify-content:center; gap:.55rem; font-weight:800; border-radius:12px; padding:.8rem 1rem; transition: transform .2s, box-shadow .2s, filter .2s; }
    .btn svg{ width:1.05rem; height:1.05rem; }
    .btn-primary{ background: linear-gradient(135deg, var(--g500), var(--g600)); color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.22); }
    .btn-primary:hover{ filter:brightness(.98); transform: translateY(-1px); }
    .btn-ghost{ background:#fff; color: var(--g700); border:1px solid var(--border); }
    .btn-ghost:hover{ background: var(--g50); border-color: var(--g300); }

    .stat-tile .icon{
      width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff;
      background: linear-gradient(135deg, var(--g500), var(--g600)); box-shadow: 0 10px 24px rgba(16,185,129,.22);
    }

    table.adm{ width:100%; }
    table.adm thead th{
      font-size:.72rem; letter-spacing:.06em; text-transform:uppercase; font-weight:800;
      color:#0b1722; background: #f8fafc; border-bottom:1px solid var(--border);
      padding:.9rem 1rem; text-align:left;
    }
    table.adm tbody td{
      padding:1rem; border-bottom:1px solid #eef2f1; color:#1f2937; vertical-align:middle;
    }
    table.adm tbody tr:hover{ background:#f9fffc; }

    .icon-btn{ display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; border:1px solid var(--border); background:#fff; transition:transform .18s, border-color .2s, background-color .2s; }
    .icon-btn:hover{ transform: translateY(-1px); border-color: var(--g300); background: var(--g50); }

    /* Simple modal */
    .modal-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.35); display:none; align-items:center; justify-content:center; z-index:50; }
    .modal{ width:100%; max-width:560px; background:#fff; border:1px solid var(--border); border-radius:16px; box-shadow:0 30px 80px rgba(16,185,129,.18); }
    .modal-header{ padding:1rem 1.25rem; border-bottom:1px solid #eef2f1; }
    .modal-body{ padding:1rem 1.25rem; }
    .modal-footer{ padding:1rem 1.25rem; border-top:1px solid #eef2f1; display:flex; justify-content:flex-end; gap:.5rem; }
    .show{ display:flex !important; }
    .field{ width:100%; padding:.7rem .9rem; border:1px solid #E5E7EB; border-radius:10px; }
    .field:focus{ outline:none; border-color: var(--g300); box-shadow: 0 0 0 3px rgba(16,185,129,.20); }
    label.small{ font-size:.8rem; color:#374151; font-weight:700; margin-bottom:.35rem; display:block; }
  </style>

  <div class="grid-mask" aria-hidden="true"></div>

  <div class="container mx-auto px-6 max-w-7xl relative z-10">

    <!-- Header -->
    <div class="shell p-6 md:p-7 mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-3xl md:text-4xl font-extrabold ink">Admin Dashboard</h1>
          <p class="muted mt-1">Manage users, subscriptions, and system settings.</p>
        </div>
        <div class="text-right">
          <span class="chip chip-admin">Administrator Access</span>
          <div class="mt-2 text-xs muted">Full system control</div>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="shell p-6 stat-tile">
        <div class="flex items-center gap-4">
          <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></svg>
          </div>
          <div>
            <div class="text-sm font-medium muted">Total Users</div>
            <div class="text-2xl font-extrabold ink">{{ $users->count() + 1 }}</div>
          </div>
        </div>
      </div>

      <div class="shell p-6 stat-tile">
        <div class="flex items-center gap-4">
          <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 21h8"/><path d="M12 17v4"/><path d="M7 8h10v6H7z"/><path d="M7 4h10v4H7z"/></svg>
          </div>
          <div>
            <div class="text-sm font-medium muted">Paid Users</div>
            <div class="text-2xl font-extrabold ink">{{ $users->where('tier', 'paid')->count() }}</div>
          </div>
        </div>
      </div>

      <div class="shell p-6 stat-tile">
        <div class="flex items-center gap-4">
          <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/></svg>
          </div>
          <div>
            <div class="text-sm font-medium muted">Free Users</div>
            <div class="text-2xl font-extrabold ink">{{ $users->where('tier', 'user')->count() }}</div>
          </div>
        </div>
      </div>

      <div class="shell p-6 stat-tile">
        <div class="flex items-center gap-4">
          <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
          </div>
          <div>
            <div class="text-sm font-medium muted">Revenue</div>
            <div class="text-2xl font-extrabold ink">${{ number_format($users->where('tier', 'paid')->count() * 199) }}/mo</div>
          </div>
        </div>
      </div>
    </div>

    <!-- User Management -->
    <div class="shell p-6 md:p-7 mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <h3 class="text-xl md:text-2xl font-extrabold ink">User Management</h3>
        <div class="flex items-center gap-3">
          <button type="button" class="btn btn-ghost" data-open="#filterModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
            Filters
          </button>
          <button type="button" class="btn btn-primary" data-open="#addUserModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            Add User
          </button>
        </div>
      </div>

      <div class="overflow-x-auto rounded-xl border border-[var(--border)]">
        <table class="adm">
          <thead>
            <tr>
              <th>User</th>
              <th>Tier</th>
              <th>Status</th>
              <th>Joined</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white">
            @foreach($users as $user)
            <tr>
              <td>
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full flex items-center justify-center mr-3"
                       style="background:linear-gradient(135deg, var(--g500), var(--g600)); box-shadow:0 8px 20px rgba(16,185,129,.22);">
                    <span class="text-white font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                  </div>
                  <div>
                    <div class="text-sm font-semibold ink">{{ $user->name }}</div>
                    <div class="text-sm muted">{{ $user->email }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="chip {{ $user->tier === 'paid' ? 'chip-active' : ($user->tier === 'user' ? 'chip-admin' : '') }}">
                  {{ $user->getTierDisplayName() }}
                </span>
              </td>
              <td>
                @if($user->hasActiveSubscription())
                  <span class="chip chip-active">Active</span>
                @else
                  <span class="chip chip-inactive">Inactive</span>
                @endif
              </td>
              <td class="text-sm muted">
                {{ $user->created_at->format('M d, Y') }}
              </td>
              <td>
                <div class="flex items-center gap-2">
                  <!-- EDIT -->
                  <button type="button"
                          class="icon-btn js-edit-user"
                          title="Edit"
                          data-open="#editUserModal"
                          data-id="{{ $user->id }}"
                          data-name="{{ e($user->name) }}"
                          data-email="{{ e($user->email) }}"
                          data-tier="{{ $user->tier }}"
                          data-active="{{ $user->hasActiveSubscription() ? 1 : 0 }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/></svg>
                  </button>

                  <!-- VIEW -->
                  <a class="icon-btn" title="View" href="{{ route('admin.users.show', $user) }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </a>

                  <!-- DELETE -->
                  <form class="inline delete-user-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" data-name="{{ e($user->name) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn" title="Delete">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- System Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="shell p-6 md:p-7">
        <h3 class="text-xl md:text-2xl font-extrabold ink mb-4">System Settings</h3>
        <div class="space-y-3">
          <a href="{{ route('admin.settings.general') }}" class="btn btn-ghost w-full justify-start">
            General Settings
          </a>
          <a href="{{ route('admin.settings.security') }}" class="btn btn-ghost w-full justify-start">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Security Settings
          </a>
          <a href="{{ route('admin.settings.database') }}" class="btn btn-ghost w-full justify-start">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10"/><path d="M7 12h10"/><path d="M7 16h7"/></svg>
            Database Management
          </a>
        </div>
      </div>

      <div class="shell p-6 md:p-7">
        <h3 class="text-xl md:text-2xl font-extrabold ink mb-4">Quick Actions</h3>
        <div class="space-y-3">
          <a href="{{ route('admin.users.export') }}" class="btn btn-primary w-full justify-start" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
            Export User Data
          </a>
          <a href="{{ route('admin.analytics.index') }}" class="btn btn-ghost w-full justify-start">
            View Analytics
          </a>
          <a href="{{ route('admin.notifications.index') }}" class="btn btn-ghost w-full justify-start">
            System Notifications
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== FILTERS MODAL ========== -->
  <div id="filterModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal">
      <div class="modal-header">
        <div class="text-lg font-extrabold ink">Filter Users</div>
      </div>
      <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="modal-body space-y-4">
          <div>
            <label class="small">Tier</label>
            <select name="tier" class="field">
              <option value="">All</option>
              <option value="user">Starter</option>
              <option value="paid">Professional</option>
              <option value="admin">Administrator</option>
            </select>
          </div>
          <div>
            <label class="small">Status</label>
            <select name="active" class="field">
              <option value="">All</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="small">Joined From</label>
              <input type="date" name="joined_from" class="field">
            </div>
            <div>
              <label class="small">Joined To</label>
              <input type="date" name="joined_to" class="field">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-ghost" data-close>Cancel</button>
          <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ========== ADD USER MODAL ========== -->
  <div id="addUserModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal">
      <div class="modal-header">
        <div class="text-lg font-extrabold ink">Add User</div>
      </div>
      <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="modal-body space-y-4">
          <div>
            <label class="small">Name</label>
            <input type="text" name="name" class="field" required>
          </div>
          <div>
            <label class="small">Email</label>
            <input type="email" name="email" class="field" required>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="small">Password</label>
              <input type="password" name="password" class="field" required>
            </div>
            <div>
              <label class="small">Tier</label>
              <select name="tier" class="field" required>
                <option value="user">Starter</option>
                <option value="paid">Professional</option>
                <option value="admin">Administrator</option>
              </select>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <input id="add-active" type="checkbox" name="active" value="1">
            <label for="add-active" class="small" style="margin:0;">Active subscription</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-ghost" data-close>Cancel</button>
          <button type="submit" class="btn btn-primary">Create User</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ========== EDIT USER MODAL (reused for any row) ========== -->
  <div id="editUserModal" class="modal-backdrop" aria-hidden="true">
    <div class="modal">
      <div class="modal-header">
        <div class="text-lg font-extrabold ink">Edit User</div>
      </div>
      <form id="editUserForm" method="POST" action="{{ route('admin.users.update', 0) }}">
        @csrf
        @method('PUT')
        <div class="modal-body space-y-4">
          <div>
            <label class="small">Name</label>
            <input type="text" name="name" class="field" id="edit-name" required>
          </div>
          <div>
            <label class="small">Email</label>
            <input type="email" name="email" class="field" id="edit-email" required>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="small">Tier</label>
              <select name="tier" class="field" id="edit-tier" required>
                <option value="user">Starter</option>
                <option value="paid">Professional</option>
                <option value="admin">Administrator</option>
              </select>
            </div>
            <div class="flex items-center gap-2 mt-6">
              <input id="edit-active" type="checkbox" name="active" value="1">
              <label for="edit-active" class="small" style="margin:0;">Active subscription</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-ghost" data-close>Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      // Simple modal controller
      function openModal(sel){ document.querySelector(sel)?.classList.add('show'); }
      function closeModal(el){ el.closest('.modal-backdrop')?.classList.remove('show'); }

      document.addEventListener('click', (e) => {
        const openSel = e.target.closest('[data-open]')?.getAttribute('data-open');
        if (openSel){ e.preventDefault(); openModal(openSel); return; }

        if (e.target.matches('[data-close], [data-close] *')){
          e.preventDefault(); closeModal(e.target); return;
        }

        if (e.target.classList.contains('modal-backdrop')){
          e.preventDefault(); e.target.classList.remove('show'); return;
        }
      });

      // Delete confirm
      document.querySelectorAll('.delete-user-form').forEach(form => {
        form.addEventListener('submit', (e) => {
          const name = form.getAttribute('data-name') || 'this user';
          if(!confirm(`Delete ${name}? This cannot be undone.`)){
            e.preventDefault();
          }
        });
      });

      // Edit user modal population
      const editForm = document.getElementById('editUserForm');
      const editName = document.getElementById('edit-name');
      const editEmail = document.getElementById('edit-email');
      const editTier = document.getElementById('edit-tier');
      const editActive = document.getElementById('edit-active');

      document.querySelectorAll('.js-edit-user').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-id');
          const name = btn.getAttribute('data-name');
          const email = btn.getAttribute('data-email');
          const tier = btn.getAttribute('data-tier');
          const active = btn.getAttribute('data-active') === '1';

          // Set fields
          if(editName) editName.value = name || '';
          if(editEmail) editEmail.value = email || '';
          if(editTier) editTier.value = tier || 'user';
          if(editActive) editActive.checked = !!active;

          // Point action to correct /update/{id}
          // We rendered /update/0 above; replace trailing /0 with /{id}
          if (editForm && id){
            editForm.action = editForm.action.replace(/\/0(\/)?$/, '/' + id + '$1');
          }
        });
      });
    })();
  </script>
</section>
@endsection
