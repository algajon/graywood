@extends('layouts.app')

@section('title', 'Lead Generation Results - Agent Bookr')

@section('content')
<style>
  :root{
    --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7;
    --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857;
    --ink:#0b1722; --muted:#6b7280; --border:#CAD2C5;
  }
  .results-wrap{
    background:
      radial-gradient(900px 420px at 6% -12%, rgba(16,185,129,.10), transparent 60%),
      radial-gradient(900px 420px at 94% -12%, rgba(5,150,105,.10), transparent 60%),
      #fff;
    min-height:100vh; padding-top:120px; position:relative;
  }
  .grid-mask{ position:absolute; inset:0; pointer-events:none; background-image:
      linear-gradient(to right, rgba(4,120,87,.08) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(4,120,87,.08) 1px, transparent 1px);
    background-size:32px 32px; mask-image: radial-gradient(closest-side, #000, transparent 85%); }

  .card{ background:linear-gradient(180deg,#fff,#f8fafc); border:1px solid var(--border);
    border-radius:20px; box-shadow:0 18px 60px rgba(16,185,129,.10);
    transition:transform .22s cubic-bezier(.2,.8,.2,1), box-shadow .22s, border-color .22s;}
  .card:hover{ transform:translateY(-3px); box-shadow:0 24px 70px rgba(4,120,87,.16); border-color:#b7e4d8;}
  .ink{color:var(--ink)} .muted{color:var(--muted)}

  .btn{ display:inline-flex; align-items:center; gap:.55rem; font-weight:800; border-radius:14px;
    padding:.85rem 1.05rem; transition:transform .2s, filter .2s, box-shadow .2s;}
  .btn svg{ width:1.05rem; height:1.05rem }
  .btn-primary{ background:linear-gradient(135deg,var(--g500),var(--g600)); color:#fff; box-shadow:0 10px 24px rgba(16,185,129,.22);}
  .btn-primary:hover{ transform:translateY(-1px); filter:brightness(.98); color:#fff;}
  .btn-ghost{ background:#fff; color:var(--g700); border:1px solid var(--border); }
  .btn-ghost:hover{ background:var(--g50); border-color:var(--g300); }

  .headline-chip{ background:var(--g100); color:var(--g700); }

  .info{ background:linear-gradient(180deg,#fff,var(--g50)); border:1px solid var(--g200); border-radius:14px; }
  .status-good{ background:#ecfdf5; border:1px solid #a7f3d0; }
  .status-warn{ background:#fff7ed; border:1px solid #fed7aa; }

  .table-wrap{ overflow:auto; }
  table{ width:100%; border-collapse:separate; border-spacing:0; }
  thead th{ position:sticky; top:0; z-index:1; background:#f8fafc; text-transform:uppercase;
    letter-spacing:.06em; font-size:.72rem; color:#64748b; border-bottom:1px solid #e5e7eb; padding:.75rem .85rem; }
  tbody td{ padding:.8rem .85rem; border-bottom:1px solid #eef2f7; color:#0f172a; font-size:.95rem; }
  tbody tr:hover{ background:#f9fbfa; }

  .bar{ height:8px; background:#eef2f7; border-radius:999px; overflow:hidden; }
  .bar-fill{ height:100%; width:0%; background:linear-gradient(90deg,var(--g400),var(--g600)); transition:width .25s ease; }

  .panel-head{ background:linear-gradient(135deg,rgba(16,185,129,.12),rgba(5,150,105,.12)); border-bottom:1px solid var(--border); }

  .celebrate{ background:linear-gradient(135deg,var(--g400),var(--g600));
    border:1px solid color-mix(in oklab, var(--g500) 55%, white);
    box-shadow:0 18px 60px rgba(16,185,129,.22); border-radius:20px; color:#fff; }
</style>

<section class="results-wrap">
  <div class="grid-mask"></div>

  <div class="container mx-auto px-6 max-w-6xl relative z-10">
    <!-- Page header -->
    <div class="text-center mb-8">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold headline-chip">Results</span>
      <h1 class="mt-3 text-3xl md:text-4xl font-extrabold ink">Lead Generation Results</h1>
      <p class="mt-2 text-lg muted">Monitor progress and download your leads.</p>
    </div>

    <!-- Status + actions -->
    <div class="card p-6 mb-6">
      <div class="flex items-center justify-between flex-col md:flex-row gap-4">
        <div>
          <h2 class="text-xl md:text-2xl font-extrabold ink">Run ID: {{ $runId }}</h2>
          <p class="muted text-sm">Track your lead generation progress</p>
        </div>
        <div class="flex items-center gap-2">
          <button type="button" id="refreshBtn" class="btn btn-ghost" onclick="pollNow()">
            <svg id="refreshIcon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 12a9 9 0 1 1-3.5-7.1"/><path d="M21 3v6h-6"/>
            </svg>
            Refresh
          </button>
          <a id="exportBtn" href="{{ config('services.scraper.base') }}/runs/{{ $runId }}/export.csv" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 19h14"/></svg>
            Export CSV
          </a>
        </div>
      </div>

      <!-- Live status -->
      <div id="status" class="info mt-4 p-4 text-center rounded-lg">
        <div class="text-sm muted">
          <svg class="inline-block -mt-1 mr-1" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
          </svg>
          This may take a while, consider a cup of coffee while we generate your new deals...
        </div>
        <div class="mt-3 bar max-w-xl mx-auto" aria-hidden="true">
          <div class="bar-fill" id="barFill" style="width: 0%"></div>
        </div>
      </div>
    </div>

    <!-- Results table -->
    <div class="card overflow-hidden">
      <div class="panel-head px-6 py-4">
        <h3 class="text-lg font-extrabold ink">Generated Leads</h3>
        <p class="muted text-sm">Your scraped data will appear below.</p>
      </div>

      <div class="table-wrap">
        <table>
          <thead><tr id="cols">
            @if(!empty($results))
              @foreach(array_keys($results[0]) as $col)
                <th>{{ $col }}</th>
              @endforeach
            @endif
          </tr></thead>
          <tbody id="rows">
            @foreach($results ?? [] as $row)
              <tr>
                @foreach($row as $v)
                  <td>{{ $v ?? '' }}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Progress explainer -->
    <div class="card p-6 my-6">
      <h3 class="text-xl font-extrabold ink mb-4">What’s happening?</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="text-center">
          <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center text-white"
               style="background:linear-gradient(135deg,var(--g500),var(--g600)); box-shadow:0 8px 20px rgba(16,185,129,.22);">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
          </div>
          <h4 class="font-semibold ink">Searching</h4>
          <p class="text-sm muted">Scanning listings for relevant properties.</p>
        </div>
        <div class="text-center">
          <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center text-white"
               style="background:linear-gradient(135deg,var(--g500),var(--g600)); box-shadow:0 8px 20px rgba(16,185,129,.22);">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="7" height="16" rx="1"/><rect x="14" y="4" width="7" height="16" rx="1"/></svg>
          </div>
          <h4 class="font-semibold ink">Processing</h4>
          <p class="text-sm muted">Extracting contact information and details.</p>
        </div>
        <div class="text-center">
          <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center text-white"
               style="background:linear-gradient(135deg,var(--g500),var(--g600)); box-shadow:0 8px 20px rgba(16,185,129,.22);">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7l-8 10-5-5"/></svg>
          </div>
          <h4 class="font-semibold ink">Complete</h4>
          <p class="text-sm muted">Ready for download and use.</p>
        </div>
      </div>
    </div>

    <!-- Celebration -->
    <div id="success-celebration" class="celebrate p-8 mt-6 text-center hidden">
      <div class="text-5xl mb-3">🎉</div>
      <h3 class="text-2xl font-extrabold">Lead generation complete!</h3>
      <p class="mt-1 text-white/90">Your leads are ready for download and CRM import.</p>
      <div class="mt-5 flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ config('services.scraper.base') }}/runs/{{ $runId }}/export.csv" class="btn bg-white text-emerald-700">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 19h14"/></svg>
          Download CSV
        </a>
        <a href="{{ route('scrapes.index') }}" class="btn btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
          Start New Search
        </a>
      </div>
    </div>
  </div>
</section>

<script>
  const base   = "{{ config('services.scraper.base') }}";
  const runId  = "{{ $runId }}";
  const init   = @json($results ?? []);
  let   done   = false;

  const elCols = document.getElementById('cols');
  const elRows = document.getElementById('rows');
  const elFill = document.getElementById('barFill');
  const elStat = document.getElementById('status');
  const elParty= document.getElementById('success-celebration');

  function escapeHtml(s){
    return String(s ?? '')
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
  }

  function renderTable(rows){
    if (!Array.isArray(rows)) return;
    if (!rows.length){ elRows.innerHTML=''; return; }
    const keys = Object.keys(rows[0]);
    elCols.innerHTML = keys.map(k=>`<th>${escapeHtml(k)}</th>`).join('');
    elRows.innerHTML = rows.map(r => {
      return `<tr>${keys.map(k=>`<td>${escapeHtml(r[k])}</td>`).join('')}</tr>`;
    }).join('');
  }

  function markComplete(count){
    done = true;
    elStat.className = 'status-good rounded-lg p-4 text-center';
    elStat.innerHTML = `
      <div class="flex items-center justify-center gap-3">
        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full"
              style="background:linear-gradient(135deg, var(--g500), var(--g600));">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="white" stroke-width="2"><path d="M20 7l-8 10-5-5"/></svg>
        </span>
        <div class="text-left">
          <div class="font-semibold" style="color:var(--g700)">Done</div>
          <div class="muted text-sm">Found ${count} leads</div>
        </div>
      </div>`;
    elParty && elParty.classList.remove('hidden');
    elFill && (elFill.style.width = '100%');
  }

  async function poll(){
    try{
      const status = await fetch(`${base}/runs/${runId}`).then(r=>r.json());
      const data   = await fetch(`${base}/runs/${runId}/results`).then(r=>r.json());
      const rows   = Array.isArray(data.results) ? data.results : [];
      renderTable(rows);

      // progress if the service reports a count
      if (typeof status.count === 'number' && elFill){
        const pct = Math.max(0, Math.min(100, status.count ? Math.round((rows.length / Math.max(status.count, rows.length)) * 100) : (rows.length ? 100 : 0)));
        elFill.style.width = pct + '%';
      }

      if ((status.status === 'succeeded') || (rows.length > 0 && status.count === rows.length)){
        markComplete(rows.length);
        return;
      }
    }catch(e){
      console.error(e);
    }
    if (!done) setTimeout(poll, 5000);
  }

  function pollNow(){
    const btn  = document.getElementById('refreshBtn');
    const icon = document.getElementById('refreshIcon');
    btn && (btn.disabled = true);
    icon && icon.classList.add('animate-spin');
    poll().finally(()=>{
      btn && (btn.disabled = false);
      icon && icon.classList.remove('animate-spin');
    });
  }

  // Initial hydrate + start polling
  renderTable(init);
  if (!init.length) poll(); // if server sent nothing, start polling
</script>
@endsection
