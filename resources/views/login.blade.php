@extends('layouts.app')

@section('title', 'Login - Agent Bookr')

@section('content')
<style>
  :root{
    --brand-50:#f0fdf4;--brand-100:#dcfce7;--brand-200:#bbf7d0;--brand-300:#86efac;
    --brand-400:#34d399;
    /* New-scrape emeralds */
    --brand-500:#10b981; /* bright */
    --brand-600:#059669; /* deep */
    --brand-700:#047857;
    --brand-800:#166534;--ink-900:#0b1722;
  }
  .login-wrap{
    background:
      radial-gradient(1200px 600px at 10% -10%, var(--brand-100), transparent),
      radial-gradient(900px 500px at 90% -20%, var(--brand-200), transparent),
      linear-gradient(135deg, #ecfdf5 0%, #ffffff 60%);
  }
  .card-glass{
    backdrop-filter: saturate(140%) blur(8px);
    background: rgba(255,255,255,.9);
    box-shadow: 0 18px 60px rgba(16,185,129,.18);
    border: 1px solid var(--brand-100);
  }
  .btn{display:inline-flex;align-items:center;justify-content:center;gap:.6rem;font-weight:700;border-radius:1rem;padding:.9rem 1.1rem;transition:transform .2s,box-shadow .2s,background-color .2s, filter .2s;}
  /* ✅ Gradient to match “New scrape” */
  .btn-primary{
    color:#fff;
    background:
      linear-gradient(180deg, rgba(255,255,255,.12), rgba(0,0,0,.06)) padding-box,
      linear-gradient(135deg, var(--brand-500), var(--brand-600)) border-box;
    border:1px solid transparent;
    box-shadow:0 10px 24px rgba(16,185,129,.28), inset 0 -2px 0 rgba(0,0,0,.12);
  }
  .btn-primary:hover{transform:translateY(-1px);filter:brightness(.98) saturate(1.03)}
  .btn-ghost{background:#fff;color:var(--brand-800);border:1px solid var(--brand-200);}
  .btn-ghost:hover{background:var(--brand-50);}
  .field{border:1px solid #e5e7eb;border-radius:14px;padding:.85rem 1rem;transition:border-color .2s, box-shadow .2s;outline:0;width:100%;}
  .field:focus{border-color:var(--brand-300);box-shadow:0 0 0 4px rgba(16,185,129,.18);}
  .icon-left{position:absolute;inset:0 auto 0 .9rem;display:flex;align-items:center;color:var(--brand-700)}
  .pl-icon{padding-left:2.35rem;}
  /* Step transitions */
  .step{opacity:0;transform:translateY(8px);pointer-events:none;max-height:0;overflow:hidden;transition:opacity .22s ease, transform .22s ease, max-height .22s ease;}
  .step.active{opacity:1;transform:translateY(0);pointer-events:auto;max-height:600px;}
  /* Progress dots */
  .dot{width:10px;height:10px;border-radius:9999px;background:#e2e8f0}
  .dot.active{background:var(--brand-600)}
  /* Password toggle button */
  .pw-toggle{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);padding:.35rem .5rem;border-radius:.6rem;border:1px solid var(--brand-200);color:var(--brand-800);background:#fff}
  .pw-toggle:hover{background:var(--brand-50)}
</style>

<section class="login-wrap min-h-screen flex items-center justify-center relative pt-32 pb-20">
  <!-- Soft grid mask -->
  <div class="absolute inset-0 pointer-events-none" style="
    background-image: linear-gradient(to right, rgba(22,101,52,.06) 1px, transparent 1px),
                      linear-gradient(to bottom, rgba(22,101,52,.06) 1px, transparent 1px);
    background-size: 32px 32px; mask-image: radial-gradient(closest-side, #000, transparent 85%);
  "></div>

  <div class="container mx-auto px-6 relative z-10">
    <div class="max-w-lg mx-auto">
      <!-- Card -->
      <div class="card-glass rounded-3xl p-8 md:p-10">
        <!-- Heading -->
        <div class="text-center">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
               style="background:var(--brand-100); color:var(--brand-800); border:1px solid var(--brand-200)">
            Welcome back
          </div>
          <h1 class="mt-3 text-3xl md:text-4xl font-extrabold tracking-tight" style="color:var(--ink-900)">
            Sign in to your account
          </h1>
          <p class="mt-2 text-slate-600">Use your email to get started.</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6" id="login-form" novalidate>
          @csrf

          <!-- STEP 1: EMAIL -->
          <div id="step-email" class="step active">
            <label for="email" class="block text-sm font-semibold mb-2" style="color:var(--ink-900)">Email address</label>
            <div class="relative">
              <span class="icon-left">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>
                </svg>
              </span>
              <input type="email"
                     id="email"
                     name="email"
                     value="{{ old('email') }}"
                     class="field pl-icon"
                     placeholder="you@company.com"
                     required
                     autocomplete="email"
                     inputmode="email">
            </div>
            @error('email')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="mt-6 flex items-center gap-3">
              <button type="button" id="continue-btn" class="btn btn-primary w-full">
                Continue
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
              </button>
            </div>
          </div>

          <!-- STEP 2: PASSWORD -->
          <div id="step-password" class="step">
            <div class="flex items-center justify-between">
              <label for="password" class="block text-sm font-semibold" style="color:var(--ink-900)">Password</label>
            </div>
            <div class="relative">
              <span class="icon-left">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
              </span>
              <input type="password"
                     id="password"
                     name="password"
                     class="field pl-icon pr-16"
                     placeholder="Your password"
                     required
                     autocomplete="current-password">
              <button type="button" id="toggle-pw" class="pw-toggle text-xs">Show</button>
            </div>
            @error('password')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="mt-4 flex items-center justify-between">
              <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" id="remember" name="remember"
                       class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                       {{ old('remember') ? 'checked' : '' }}>
                Remember me
              </label>
              <a href="{{ Route::has('password.request') ? route('password.request') : '#' }}"
                 class="text-sm font-semibold text-emerald-700 hover:underline">
                Forgot password?
              </a>
            </div>

            <div class="mt-6">
              <button type="submit" class="btn btn-primary w-full">
                Sign In
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M10 17l5-5-5-5"/><path d="M4 12h11"/>
                </svg>
              </button>
            </div>
          </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-slate-700">
          <p>
            Don’t have an account?
            <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:underline">Sign up for free</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  const emailStep = document.getElementById('step-email');
  const passwordStep = document.getElementById('step-password');
  const continueBtn = document.getElementById('continue-btn');
  const backBtn = document.getElementById('back-btn');
  const emailInput = document.getElementById('email');
  const pwInput = document.getElementById('password');
  const dotEmail = document.getElementById('dot-email');
  const dotPw = document.getElementById('dot-password');
  const togglePw = document.getElementById('toggle-pw');

  function toPassword(){
    if (!emailInput.checkValidity()) { emailInput.reportValidity(); return; }
    emailStep.classList.remove('active');
    passwordStep.classList.add('active');
    dotEmail && dotEmail.classList.remove('active'); 
    dotPw && dotPw.classList.add('active');
    setTimeout(()=>pwInput.focus(), 120);
  }
  function toEmail(){
    passwordStep.classList.remove('active');
    emailStep.classList.add('active');
    dotPw && dotPw.classList.remove('active'); 
    dotEmail && dotEmail.classList.add('active');
    setTimeout(()=>emailInput.focus(), 120);
  }

  continueBtn && continueBtn.addEventListener('click', toPassword);
  backBtn && backBtn.addEventListener('click', toEmail);

  // Enter key on email goes to password step
  emailInput && emailInput.addEventListener('keydown', (e)=>{
    if(e.key === 'Enter'){ e.preventDefault(); toPassword(); }
  });

  // Toggle password visibility
  togglePw && togglePw.addEventListener('click', ()=>{
    if(pwInput.type === 'password'){ pwInput.type = 'text'; togglePw.textContent = 'Hide'; }
    else { pwInput.type = 'password'; togglePw.textContent = 'Show'; }
    pwInput.focus();
  });

  // If there were validation errors on password or email, open password step automatically
  @if ($errors->has('password') || (old('email') && $errors->any()))
    document.addEventListener('DOMContentLoaded', toPassword);
  @endif
})();
</script>
@endsection
