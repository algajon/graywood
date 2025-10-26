<!-- ======================= FOOTER ======================= -->
<footer class="ab-footer relative pt-16">
  <style>
    /* Footer theme wrapper */
    .ab-footer{ background: var(--ab-surface); color: var(--ab-text); }
    html.dark .ab-footer{
      background: linear-gradient(180deg, var(--hero-top) 0%, var(--hero-bottom) 100%);
    }
    .ab-footer .footer-divider{ border-color: var(--ab-border); }
    html.dark .ab-footer .text-slate-700,
    html.dark .ab-footer .text-slate-600{ color: var(--ab-muted) !important; }

    /* === Unified button system (matches site-wide green) === */
    .ab-footer .btn{
      display:inline-flex; align-items:center; gap:.55rem;
      font-weight:700; border-radius:.9rem; padding:.7rem 1.1rem;
      transition: transform .2s, box-shadow .2s, filter .2s, background-color .2s, color .2s, border-color .2s;
    }
    .ab-footer .btn svg{ width:1.05rem; height:1.05rem; }

    .ab-footer .btn-primary{
      background: var(--brand-600, #10b981); color:#fff;
      box-shadow: 0 10px 24px rgba(16,185,129,.22);
    }
    .ab-footer .btn-primary:hover{ background: var(--brand-700, #10b981); color:#fff; transform: translateY(-1px); }
    html.dark .ab-footer .btn-primary{ background: var(--ab-primary:#10b981;); color:#fff; }
    html.dark .ab-footer .btn-primary:hover{ background: var(--ab-primary-700:#059669;); }

    .ab-footer .btn-invert{
      background:#fff; color: var(--brand-800,#166534); border:1px solid var(--brand-200,#bbf7d0);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.6);
    }
    .ab-footer .btn-invert:hover{ background:#f8fafc; }
    html.dark .ab-footer .btn-invert{
      background: var(--ab-primary,#16a34a); color:#fff; border-color: transparent;
      box-shadow: 0 10px 24px rgba(16,185,129,.22);
    }
    html.dark .ab-footer .btn-invert:hover{ filter: brightness(.95); }

    .ab-footer a:hover{ color: var(--brand-700,#15803d); }

    .ab-footer .bg-accent-grid{
      background-image:
        linear-gradient(to right, rgba(22,101,52,.06) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(22,101,52,.06) 1px, transparent 1px);
      background-size: 32px 32px;
      mask-image: radial-gradient(closest-side, #000, transparent 88%);
    }
  </style>

  <!-- Soft gradient + grid mask -->
  <div class="absolute inset-0 pointer-events-none">
    <div class="absolute inset-0"
         style="background:
           radial-gradient(900px 400px at 10% -10%, rgba(16,185,129,.10), transparent 60%),
           radial-gradient(900px 400px at 90% 0%, rgba(16,185,129,.08), transparent 60%);"></div>
    <div class="absolute inset-0 bg-accent-grid"></div>
  </div>

  @php
    $hidePricingCtas = auth()->check() && in_array(auth()->user()->tier ?? null, ['paid','admin']);
  @endphp

  <div class="relative container mx-auto px-6">
    <div class="grid gap-10 md:gap-12 md:grid-cols-4">

      <!-- Brand / CTA -->
      <div>
        <div class="flex items-center gap-3 mb-4">
          <h3 class="text-2xl font-extrabold tracking-tight" style="color:var(--ink-900)">Agent Bookr</h3>
        </div>
        <p class="text-slate-600 leading-relaxed">
          The modern suite for real-estate lead discovery and enrichment. Scrape smarter, scale faster, and sync clean data to your CRM.
        </p>

        @if(!$hidePricingCtas)
          <div class="mt-6 flex gap-3">
            <a href="{{ route('book') }}" class="btn btn-primary">
              Start free trial
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ Route::has('pricing') ? route('pricing') : url('/pricing') }}" class="btn btn-invert">
              Pricing
            </a>
          </div>
        @endif
      </div>

      <!-- Solutions -->
      <div>
        <h4 class="text-lg font-semibold mb-4" style="color:var(--ink-900)">Solutions</h4>
        <ul class="space-y-2">
          <li><a href="/scrapes/" class="text-slate-700 hover:text-emerald-700">Lead Generation</a></li>
          <li><a href="/resources/scripts" class="text-slate-700 hover:text-emerald-700">Cold Calling Scripts</a></li>
          <li><a href="/resources/tutorials" class="text-slate-700 hover:text-emerald-700">Tutorials</a></li>
          <li><a href="/terms" class="text-slate-700 hover:text-emerald-700">Terms & Conditions</a></li>
          <!-- <li><a href="#" class="text-slate-700 hover:text-emerald-700">Analytics</a></li> -->
        </ul>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="text-lg font-semibold mb-4" style="color:var(--ink-900)">Quick Links</h4>
        <ul class="space-y-2">
          @if(!$hidePricingCtas)
            <li><a href="{{ Route::has('pricing') ? route('pricing') : url('/pricing') }}" class="text-slate-700 hover:text-emerald-700">Pricing</a></li>
          @endif
          <li><a href="/resources" class="text-slate-700 hover:text-emerald-700">Resources</a></li>
          <li><a href="/#about" data-scrollto="#about" class="text-slate-700 hover:text-emerald-700">About Us</a></li>
          <!-- <li><a href="#" class="text-slate-700 hover:text-emerald-700">Training</a></li> -->
          <li><a href="#" class="text-slate-700 hover:text-emerald-700">Support</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h4 class="text-lg font-semibold mb-4" style="color:var(--ink-900)">Contact</h4>
        <ul class="space-y-3 text-slate-700">
          <!-- <li class="flex items-start gap-3">
            <span class="mt-0.5 inline-flex w-6 justify-center text-emerald-700">
              <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            </span>
            <span>123 Tech Drive, Suite 100<br>Austin, TX 78701</span>
          </li> -->
          <li class="flex items-center gap-3">
            <span class="inline-flex w-6 justify-center text-emerald-700">
              <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.09 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.6 2.5a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.58-1.12a2 2 0 0 1 2.11-.45c.8.28 1.64.48 2.5.6A2 2 0 0 1 22 16.92z"/></svg>
            </span>
            <span>+1 (705) 309-9740</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="inline-flex w-6 justify-center text-emerald-700">
              <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
            </span>
            <span>david@greywoodpm.com</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="my-12 pt-8 footer-divider border-t text-center">
      <p class="text-slate-600">&copy; {{ now()->year }} Agent Bookr. All rights reserved.</p>
    </div>
  </div>
</footer>
