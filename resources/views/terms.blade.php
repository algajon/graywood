@extends('layouts.app')

@section('title', 'Terms & Conditions — Agent Bookr')

@section('content')
<style>
  :root{
    --g50:#ecfdf5; --g100:#d1fae5; --g200:#a7f3d0; --g300:#6ee7b7;
    --g400:#34d399; --g500:#10b981; --g600:#059669; --g700:#047857;
    --ink:#0b1722; --muted:#6b7280; --border:#CAD2C5;
  }
  .legal-wrap{
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
  .card{ background:linear-gradient(180deg,#fff,#f8fafc); border:1px solid var(--border);
    border-radius:18px; box-shadow:0 18px 60px rgba(16,185,129,.10); }
  .ink{color:var(--ink)} .muted{color:var(--muted)}
  .section h3{ color:var(--ink); font-weight:900; }
  .section p, .section li{ color:#0f172a; }
  .badge{ display:inline-flex; align-items:center; gap:.4rem; font-weight:700; font-size:.72rem; padding:.25rem .6rem; border-radius:999px; background:var(--g100); color:var(--g700); }
</style>

<section class="legal-wrap">
  <div class="grid-mask"></div>

  <div class="container mx-auto px-6 max-w-4xl relative z-10">
    <!-- Header -->
    <div class="text-center mb-8">
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold badge">Legal</span>
      <h1 class="mt-3 text-3xl md:text-4xl font-extrabold ink">Terms &amp; Conditions</h1>
      <p class="muted mt-1">Last updated: {{ date('F j, Y') }}</p>
    </div>

    <div class="card p-6 md:p-8 section space-y-8">
      <!-- <p class="muted">
        These Terms &amp; Conditions (the “Terms”) govern your use of {{ config('app.name', 'Agent Bookr') }}
        (the “Service”). By accessing the Service you agree to these Terms. If you don’t agree, don’t use the Service.
        This is not legal advice — it’s the usual “bla bla bla” but tailored to white-label web scraping and lead generation.
      </p> -->

      <div>
        <h3 class="text-xl mb-2">1) What we do (white-label scraping)</h3>
        <p>
          We provide automation that collects publicly available information from third-party websites that you
          specify (“Target Sites”) and exports it to CSV/JSON and integrations. The Service is white-label:
          you can use exports in your own brand. We’re not affiliated with Target Sites and we don’t claim ownership of their content.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">2) Your responsibilities</h3>
        <ul class="list-disc pl-5 space-y-2">
          <li>You confirm you have the right to access each Target Site and to process any resulting data for your intended use.</li>
          <li>You will not use the Service to bypass paywalls, logins, or technical access controls without permission.</li>
          <li>You will respect applicable laws (including CFAA/anti-hacking laws), platform policies, and any binding court orders.</li>
          <li>You will use leads compliantly (e.g., CAN-SPAM, CASL, TCPA, GDPR/UK-GDPR as applicable) and honor opt-outs.</li>
          <li>You won’t scrape sensitive categories (e.g., health, minors, precise geolocation) or PII where prohibited.</li>
        </ul>
      </div>

      <div>
        <h3 class="text-xl mb-2">3) Robots.txt &amp; rate limits</h3>
        <p>
          Unless you demonstrate a contractual right to ignore them, you must honor Target Site rate-limits and robots.txt directives.
          We may throttle, queue, or stop jobs to avoid undue load. You won’t attempt to overwhelm Target Sites.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">4) Data ownership &amp; license</h3>
        <ul class="list-disc pl-5 space-y-2">
          <li>As between you and us, you own the exports we generate from your jobs.</li>
          <li>You grant us a limited license to process URLs, run scrapers, cache transient HTML/results, and operate the Service.</li>
          <li>We don’t sell your exported data. We may use anonymized aggregates to improve reliability and detection controls.</li>
        </ul>
      </div>

      <div>
        <h3 class="text-xl mb-2">5) White-label &amp; branding</h3>
        <ul class="list-disc pl-5 space-y-2">
          <li>You may present the outputs under your own brand.</li>
          <li>You may not use Target Site logos, trademarks, or suggest endorsement by those sites.</li>
          <li>Don’t remove or obscure any legally required notices in exported content (where applicable).</li>
        </ul>
      </div>

      <div>
        <h3 class="text-xl mb-2">6) Acceptable use</h3>
        <ul class="list-disc pl-5 space-y-2">
          <li>No malware, credential harvesting, spam campaigns, stalking, discrimination, or other unlawful activity.</li>
          <li>No attempting to reverse engineer or resell the Service without our written consent.</li>
          <li>No scraping of content explicitly marked confidential or accessible only via misrepresentation.</li>
        </ul>
      </div>

      <div>
        <h3 class="text-xl mb-2">7) Third-party infrastructure</h3>
        <p>
          We may use proxies, headless browsers, and cloud providers to run jobs. Outages or blocks sometimes happen —
          that’s normal scraping life. We’ll act reasonably to restore service but do not guarantee uninterrupted availability.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">8) Service level, beta features</h3>
        <p>
          The Service is provided “as is” without warranties. Beta features may change, break, or vanish. We’re not liable
          for lost profits, lost data, or consequential damages to the maximum extent permitted by law.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">9) Takedowns &amp; complaints</h3>
        <p>
          If a rights holder asks that we stop hitting a Target Site or remove content from your exports, we may pause
          or modify jobs while we review. Send notices to
          <a href="mailto:david@greywoodpm.com" class="underline text-emerald-700">david@greywoodpm.com</a>.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">10) Term &amp; termination</h3>
        <p>
          We may suspend or terminate accounts that violate these Terms. You may export your data before closing your account.
          Sections on ownership, acceptable use, limits of liability, and indemnities survive termination.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">11) Privacy &amp; data processing</h3>
        <p>
          We process limited personal data to operate the Service. For controller/processor roles and region-specific addenda,
          see our Privacy Notice and (if applicable) Data Processing Addendum. You’re responsible for your own law-compliant use
          of exported leads.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">12) Indemnity</h3>
        <p>
          You agree to defend and indemnify {{ config('app.name', 'Agent Bookr') }} against claims arising from your use of the Service,
          your Target Sites, or your exports, except to the extent caused by our gross negligence or willful misconduct.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">13) Changes to these Terms</h3>
        <p>
          We may update these Terms from time to time. Material changes will be posted here. Continued use after changes
          means you accept the updated Terms.
        </p>
      </div>

      <div>
        <h3 class="text-xl mb-2">14) Contact</h3>
        <p>
          Questions? Email <a href="mailto:david@greywoodpm.com" class="underline text-emerald-700">david@greywoodpm.com</a>.
        </p>
      </div>
    </div>
  </div>
</section>
@endsection
