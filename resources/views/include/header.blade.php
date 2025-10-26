<!-- ======================= THEME-LESS HEADER ======================= -->
<style id="ab-theme-core">
  /* ======= Agent Bookr — header/theme core (cleaned) ======= */
  :root{
    /* Neutrals */
    --ab-bg:#ffffff;
    --ab-surface:#ffffff;
    --ab-card:#ffffff;
    --ab-text:#0b1722;
    --ab-muted:#6b7280;
    --ab-border:#CAD2C5;
    --ink-900:#0b1722;

    /* Brand emerald (matches app CTAs) */
    --ab-primary:#10b981;      /* emerald-500 */
    --ab-primary-700:#059669;  /* emerald-600 */

    /* Accents & effects */
    --ab-amber:#FFB703;
    --ab-sage:#52796F;
    --ab-shadow:0 10px 30px rgba(47,62,70,.08);
    --ab-card-shadow:0 18px 60px rgba(82,121,111,.12);

    /* Hero bg helpers (kept for other sections) */
    --hero-top:#ffffff;
    --hero-bottom:#f8fafc;
    --hero-glass:rgba(255,255,255,.75);
    --hero-ring:rgba(34,197,94,.08);
  }

  html, body{ background:var(--ab-bg); color:var(--ab-text); }

  /* Bars & header */
  .ab-topbar,
  #main-header,
  #mobile-menu .mobile-panel{
    background:var(--ab-surface);
    color:var(--ab-text);
    border-color:var(--ab-border);
  }
  .ab-topbar{
    border-bottom:1px solid var(--ab-border);
    transition: background-color .35s, color .35s, border-color .35s;
  }
  #main-header{
    border-bottom:1px solid var(--ab-border);
    box-shadow:var(--ab-shadow);
    transition: background-color .35s, color .35s, border-color .35s, box-shadow .35s;
  }
  #main-header.is-scrolled{
    background: color-mix(in oklab, var(--ab-surface) 96%, transparent);
  }

  /* Buttons */
  .ab-btn-outline,
  .ab-btn-primary{
    border-radius:12px;
    padding:.5rem 1rem;
    font-weight:700;
    line-height:1.25;
    transition: background-color .2s, color .2s, border-color .2s, box-shadow .2s, filter .2s, transform .2s;
  }
  .ab-btn-outline{
    border:1px solid var(--ab-border);
    background:#fff;
    color:var(--ab-text);
  }
  .ab-btn-outline:hover{
    background: color-mix(in oklab, #000 6%, transparent);
  }

  /* Primary = same emerald gradient as app CTAs */
  .ab-btn-primary{
    color:#fff;
    background:
      linear-gradient(180deg, rgba(255,255,255,.12), rgba(0,0,0,.06)) padding-box,
      linear-gradient(135deg, var(--ab-primary), var(--ab-primary-700)) border-box;
    border:1px solid transparent;
    box-shadow:0 10px 24px rgba(16,185,129,.22);
  }
  .ab-btn-primary:hover,
  .ab-btn-primary:focus{
    filter:brightness(.98) saturate(1.02);
    transform: translateY(-1px);
    color:#fff;
  }
  .ab-btn-outline svg,
  .ab-btn-primary svg{ stroke:currentColor; }

  /* Run Scraper pill (uses same gradient, slightly bolder) */
  .ab-btn-run{
    display:inline-flex; align-items:center; gap:.55rem;
    padding:.55rem .95rem; border-radius:12px; font-weight:800;
    color:#fff;
    background:
      linear-gradient(180deg, rgba(255,255,255,.10), rgba(0,0,0,.08)) padding-box,
      linear-gradient(135deg, var(--ab-primary), var(--ab-primary-700)) border-box;
    border:1px solid transparent;
    box-shadow:0 10px 22px rgba(16,185,129,.22);
    transition: transform .2s, box-shadow .2s, filter .2s, background-color .2s;
  }
  .ab-btn-run:hover{
    transform: translateY(-1px);
    filter:brightness(.98);
    box-shadow:0 16px 30px rgba(16,185,129,.28);
    color:#fff;
  }
  .ab-btn-run:focus-visible{
    outline:2px solid color-mix(in oklab, var(--ab-primary) 60%, white);
    outline-offset:2px;
  }
  .ab-btn-run svg{ width:1.05rem; height:1.05rem; stroke:currentColor; }

  /* Mobile menu motion */
  #mobile-menu{ transition: opacity .25s; }
  #mobile-menu.hidden{ opacity:0; pointer-events:none; }
  #mobile-menu .mobile-panel{ transform: translateX(-100%); transition: transform .28s cubic-bezier(.2,.8,.2,1); }
  #mobile-menu.open .mobile-panel{ transform: translateX(0); }

  /* Utilities */
  .price{ color:var(--ab-text); }
  .muted{ color:var(--ab-muted); }
  .ab-brand, .ab-brand:visited{ color:var(--ink-900); text-decoration:none; }

  /* Divider (desktop only) */
  .ab-divider{ display:inline-block; width:1px; height:26px; background:var(--ab-border); }
  @media (max-width:1024px){ .ab-divider{ display:none; } }
</style>


<!-- ======================= PHONE BAR ======================= -->
<div class="ab-topbar fixed top-0 left-0 right-0 z-50">
  <div class="container mx-auto px-4 py-2">
    <div class="flex justify-between items-center text-sm">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
        <span class="font-semibold">david@greywoodpm.com</span>
      </div>
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.09 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.6 2.5a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.58-1.12a2 2 0 0 1 2.11-.45c.8.28 1.64.48 2.5.6A2 2 0 0 1 22 16.92z"/></svg>
        <span class="font-semibold">+1 (705) 309-9740</span>
      </div>
    </div>
  </div>
</div>

<!-- ======================= HEADER ======================= -->
<header id="main-header" class="fixed top-[32px] left-0 right-0 z-50">
  <div class="container mx-auto px-4 py-3">
    <div class="flex items-center justify-between">
      <a href="/" class="ab-brand text-2xl font-extrabold tracking-tight">Agent Bookr</a>

      <nav class="hidden lg:flex items-center gap-6">
        <div class="flex items-center gap-4">
          <a href="{{ Route::has('pricing') ? route('pricing') : url('/pricing') }}" class="font-medium" style="color: var(--ab-text);">Pricing</a>
          <a href="/resources" class="font-medium" style="color: var(--ab-text);">Resources</a>

          <!-- Simple in-page links (no dropdowns) -->
          <a href="/#solutions" data-scrollto="#solutions" class="font-medium" style="color: var(--ab-text);">Solutions</a>
          <a href="/#about"     data-scrollto="#about"     class="font-medium" style="color: var(--ab-text);">About</a>
        </div>

        <!-- Auth / Actions -->
        <div class="-ml-2 flex items-center gap-3">
          @guest
            <a href="{{ route('login') }}" class="ab-btn-outline">Login</a>
            <a href="{{ route('register') }}" class="ab-btn-primary">Sign Up</a>
          @else
            <!-- Run Scraper button with given URL -->
            <a href="/scrapes/" class="ab-btn-run" title="Run Scraper" aria-label="Run Scraper">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M8 5v14l11-7-11-7z"/>
              </svg>
              <span class="hidden sm:inline">Run Scraper</span>
            </a>

            <!-- subtle divider to separate from user controls -->
            <span class="ab-divider mx-1"></span>

            <a href="{{ route('dashboard') }}" class="font-medium" style="color: var(--ab-text);">
              {{ Auth::user()->name }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" class="ab-btn-outline">Logout</button>
            </form>
          @endguest
        </div>
      </nav>

      <button id="mobile-menu-btn" 
        class="lg:hidden inline-flex items-center justify-center w-12 h-12 ab-btn-outline">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 6h18M3 12h18M3 18h18"/>
        </svg>
      </button>
    </div>
  </div>
</header>

<!-- ======================= MOBILE MENU ======================= -->
<div id="mobile-menu" class="lg:hidden fixed inset-0 z-[60] hidden">
  <div class="absolute inset-0 bg-black/50" id="mobile-overlay"></div>
  <div class="mobile-panel relative h-full w-80 border">
    <div class="flex items-center justify-between p-4 border-b">
      <a href="/" class="ab-brand text-xl font-extrabold tracking-tight">Agent Bookr</a>
      <button id="mobile-close" class="ab-btn-outline inline-flex items-center justify-center w-12 h-12" aria-label="Close menu">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M6 6l12 12M6 18L18 6"/>
        </svg>
      </button>
    </div>

    <nav class="p-4 space-y-2">
      <a href="{{ Route::has('pricing') ? route('pricing') : url('/pricing') }}" class="block rounded-lg px-4 py-3 hover:bg-[#CAD2C5]/30">Pricing</a>
      <a href="/resources" class="block rounded-lg px-4 py-3 hover:bg-[#CAD2C5]/30">Resources</a>

      <!-- Simple links for sections -->
      <a href="/#solutions" data-scrollto="#solutions" class="block rounded-lg px-4 py-3 hover:bg-[#CAD2C5]/30">Solutions</a>
      <a href="/#about"     data-scrollto="#about"     class="block rounded-lg px-4 py-3 hover:bg-[#CAD2C5]/30">About</a>

      <div class="pt-2 border-t mt-2 space-y-2">
        @guest
          <a href="{{ route('login') }}" class="ab-btn-outline w-full inline-flex justify-center">Login</a>
          <a href="{{ route('register') }}" class="ab-btn-primary w-full inline-flex justify-center">Sign Up</a>
        @else
          <!-- Mobile Run Scraper (uses the same given URL) -->
          <a href="{{ route('dashboard') }}" class="block rounded-lg px-4 py-3 hover:bg-[#CAD2C5]/30">Dashboard</a>
          <a href="/scrapes/" class="ab-btn-run w-full inline-flex justify-center mb-2" title="Run Scraper" aria-label="Run Scraper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M8 5v14l11-7-11-7z"/>
            </svg>
            Run Scraper
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="ab-btn-outline w-full inline-flex justify-center">Logout</button>
          </form>
        @endguest
      </div>
    </nav>
  </div>
</div>

<script>
(function () {
  if (window.__AB_HEADER_INIT__) return;
  window.__AB_HEADER_INIT__ = true;

  const header     = document.getElementById('main-header');
  const mobBtn     = document.getElementById('mobile-menu-btn');
  const mobMenu    = document.getElementById('mobile-menu');
  const mobClose   = document.getElementById('mobile-close');
  const mobOverlay = document.getElementById('mobile-overlay');

  const onScroll = () => { (window.scrollY > 4) ? header.classList.add('is-scrolled') : header.classList.remove('is-scrolled'); };
  onScroll(); window.addEventListener('scroll', onScroll, { passive: true });

  const openMobile  = () => { if (!mobMenu) return; mobMenu.classList.remove('hidden'); setTimeout(() => mobMenu.classList.add('open'), 10); };
  const closeMobile = () => { if (!mobMenu) return; mobMenu.classList.remove('open'); setTimeout(() => mobMenu.classList.add('hidden'), 250); };
  mobBtn     && mobBtn.addEventListener('click', openMobile);
  mobClose   && mobClose.addEventListener('click', closeMobile);
  mobOverlay && mobOverlay.addEventListener('click', closeMobile);

  // Smooth in-page scroll for header links when already on the homepage.
  function smartScroll(e){
    const targetSel = e.currentTarget.getAttribute('data-scrollto');
    if (!targetSel) return;
    const onHome = location.pathname === '/' || location.pathname === '/index' || location.pathname === '/home';
    if (onHome){
      const el = document.querySelector(targetSel);
      if (el){
        e.preventDefault();
        el.scrollIntoView({behavior:'smooth', block:'start'});
        // close mobile menu if open
        closeMobile();
      }
    }
    // else allow normal navigation to "/#id"
  }

  document.querySelectorAll('[data-scrollto]').forEach(a => a.addEventListener('click', smartScroll));
})();
</script>
