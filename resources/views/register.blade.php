@extends('layouts.app')

@section('title', 'Register - Agent Bookr')

@section('content')
<style>
  :root{
    --brand-50:#f0fdf4;--brand-100:#dcfce7;--brand-200:#bbf7d0;--brand-300:#86efac;
    --brand-400:#34d399;
    /* New-scrape emeralds */
    --brand-500:#10b981; /* bright */
    --brand-600:#059669; /* deep */
    --brand-700:#047857;
    --brand-800:#166534;--ink-900:#0b1722;--ink-700:#334155;
  }
  .wrap{
    background:
      radial-gradient(1200px 600px at 10% -10%, var(--brand-100), transparent),
      radial-gradient(900px 500px at 90% -20%, var(--brand-200), transparent),
      linear-gradient(135deg, #ecfdf5 0%, #ffffff 60%);
  }
  .card-glass{
    backdrop-filter: saturate(140%) blur(8px);
    background: rgba(255,255,255,.92);
    box-shadow: 0 18px 60px rgba(16,185,129,.16);
    border: 1px solid var(--brand-100);
  }
  .btn{display:inline-flex;align-items:center;justify-content:center;gap:.6rem;font-weight:700;border-radius:1rem;padding:.95rem 1.1rem;transition:transform .2s,box-shadow .2s,background-color .2s, filter .2s;}
  /* ✅ Gradient to match “New scrape” buttons */
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
  .field{border:1px solid #e5e7eb;border-radius:14px;padding:.9rem 1rem;transition:border-color .2s, box-shadow .2s;outline:0;width:100%;color:var(--ink-700)}
  .field::placeholder{color:#9ca3af}
  .field:focus{border-color:var(--brand-300);box-shadow:0 0 0 4px rgba(16,185,129,.18)}
  .icon-left{position:absolute;inset:0 auto 0 .9rem;display:flex;align-items:center;color:var(--brand-700)}
  .pl-icon{padding-left:2.35rem;}

  /* Tighter spacing below subtitle */
  .heading-wrap{margin-bottom: .75rem;}
  .form-wrap{margin-top: .75rem;}

  /* Step transitions */
  .step{opacity:0;transform:translateY(8px);pointer-events:none;max-height:0;overflow:hidden;transition:opacity .22s ease, transform .22s ease, max-height .22s ease;}
  .step.active{opacity:1;transform:translateY(0);pointer-events:auto;max-height:1200px;}

  /* Phone input (editable prefix) */
  .prefix-input{width:84px;border:1px solid #e5e7eb;border-right:none;border-radius:14px 0 0 14px;padding:.9rem .65rem;background:#fff;color:var(--ink-700);text-align:center}
  .phone-number{border-left:none;border-radius:0 14px 14px 0}

  /* Password toggles */
  .pw-toggle{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);padding:.35rem .6rem;border-radius:.6rem;border:1px solid var(--brand-200);color:var(--brand-800);background:#fff}
  .pw-toggle:hover{background:var(--brand-50)}

  /* Plans */
  .plan-grid{display:grid;grid-template-columns:1fr;gap:.85rem;margin-top:.15rem}
  @media (min-width:768px){ .plan-grid{grid-template-columns:repeat(2,1fr);} }
  .radio{position:absolute;opacity:0;pointer-events:none}
  .plan-card{
    position:relative;border:1px solid #e5e7eb;border-radius:20px;padding:.95rem 1rem;background:#fff;
    display:flex;flex-direction:column;gap:.45rem;min-height:172px;justify-content:center;align-items:center;
    transition:border-color .2s, box-shadow .2s, background .2s, transform .2s;
  }
  .plan-card:hover{border-color:var(--brand-300);transform:translateY(-1px)}
  .plan-header{display:flex;align-items:center;gap:.5rem}
  .plan-badge{font-size:.7rem;font-weight:800;letter-spacing:.04em;color:#065f46;background:var(--brand-100);border:1px solid var(--brand-200);padding:.18rem .5rem;border-radius:9999px}
  .plan-title{font-weight:900;color:var(--ink-900);font-size:1.25rem;text-align:center}
  .plan-price{color:#0f172a;font-size:1rem;text-align:center;margin-top:.02rem}
  .plan-feats{color:#64748b;font-size:.95rem;margin-top:.05rem}
  .plan-feats li{margin:.18rem 0}
  .plan-check{
    position:absolute;top:.55rem;right:.55rem;width:24px;height:24px;border-radius:9999px;border:2px solid #cbd5e1;background:#fff;display:flex;align-items:center;justify-content:center
  }
  .plan-check svg{width:14px;height:14px;stroke:var(--brand-600);display:none}
  .radio:checked + .plan-card{
    border-color:var(--brand-600);
    background:linear-gradient(180deg, rgba(34,197,94,.06), rgba(34,197,94,.02));
    box-shadow:0 10px 28px rgba(16,185,129,.18);
  }
  .radio:checked + .plan-card .plan-check{border-color:var(--brand-600);background:#ecfdf5}
  .radio:checked + .plan-card .plan-check svg{display:block}

  /* Plan step sits closer when active */
  #step-plan.active{ margin-top:-8px; }

  /* Terms checkbox alignment (green tick + tiny bump) */
  .terms-check{accent-color: var(--brand-600); margin-top:2px;}

  /* Link color (green, no yellow) */
  a.link{color:var(--brand-700)}
  a.link:hover{color:var(--brand-600);text-decoration:underline}
</style>

<section class="wrap min-h-screen flex items-center justify-center relative pt-32 pb-20">
  <div class="absolute inset-0 pointer-events-none" style="
    background-image: linear-gradient(to right, rgba(22,101,52,.06) 1px, transparent 1px),
                      linear-gradient(to bottom, rgba(22,101,52,.06) 1px, transparent 1px);
    background-size: 32px 32px; mask-image: radial-gradient(closest-side, #000, transparent 85%);
  "></div>

  <div class="container mx-auto px-6 relative z-10">
    <div class="max-w-2xl mx-auto">
      <div class="card-glass rounded-3xl p-8 md:p-10">
        <div class="text-center heading-wrap">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
               style="background:var(--brand-100); color:var(--brand-800); border:1px solid var(--brand-200)">
            Welcome to Agent Bookr
          </div>
          <h1 class="mt-2 text-3xl md:text-4xl font-extrabold tracking-tight" style="color:var(--ink-900)">
            Create your account
          </h1>
          <p class="mt-1 text-slate-600">We’ll guide you step by step.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="form-wrap space-y-2" id="register-form" novalidate>
          @csrf
          <input type="hidden" name="redirect_to" id="redirect_to" value="/dashboard">
          <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">

          <!-- STEP 1: EMAIL -->
          <div id="step-email" class="step {{ $errors->any() ? '' : 'active' }}">
            <label for="email" class="block text-sm font-semibold mb-2" style="color:var(--ink-900)">Email address *</label>
            <div class="relative">
              <span class="icon-left">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>
                </svg>
              </span>
              <input type="email" id="email" name="email" value="{{ old('email') }}"
                     class="field pl-icon" placeholder="you@company.com" required autocomplete="email" inputmode="email">
            </div>
            @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="mt-5"><button type="button" id="to-name" class="btn btn-primary w-full">Continue</button></div>
          </div>

          <!-- STEP 2: NAME -->
          <div id="step-name" class="step">
            <div class="flex items-center justify-between">
              <label for="name" class="block text-sm font-semibold" style="color:var(--ink-900)">Full name *</label>
              <button type="button" class="text-sm font-semibold link" id="back-email">Change email</button>
            </div>
            <div class="relative mt-2">
              <span class="icon-left">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-3-3.87M4 21v-2a4 4 0 0 1 3-3.87"/><circle cx="12" cy="7" r="4"/>
                </svg>
              </span>
              <input type="text" id="name" name="name" value="{{ old('name') }}" class="field pl-icon"
                     placeholder="Your name" required autocomplete="name">
            </div>
            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="mt-5"><button type="button" id="to-phone" class="btn btn-primary w-full">Continue</button></div>
          </div>

          <!-- STEP 3: PHONE -->
          <div id="step-phone" class="step">
            <div class="flex items-center justify-between">
              <label class="block text-sm font-semibold" style="color:var(--ink-900)">Phone number *</label>
              <button type="button" class="text-sm font-semibold link" id="back-name">Back</button>
            </div>

            <div class="mt-2 flex">
              <input type="text" id="phone_prefix" class="prefix-input" value="{{ old('phone_prefix', '+1') }}"
                     placeholder="+1" pattern="^\+?[0-9]{1,4}$" title="Dial code like +1, +44, +61" required>
              <input type="tel" id="phone_number" class="field phone-number"
                     value="{{ old('phone_number') }}" placeholder="555 123 4567"
                     pattern="^[0-9\s\-().]{7,20}$" required inputmode="tel" autocomplete="tel">
            </div>
            @error('phone') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="mt-5"><button type="button" id="to-password" class="btn btn-primary w-full">Continue</button></div>
          </div>

          <!-- STEP 4: PASSWORDS -->
          <div id="step-passwords" class="step">
            <div class="flex items-center justify-between">
              <label class="block text-sm font-semibold" style="color:var(--ink-900)">Create password *</label>
              <button type="button" class="text-sm font-semibold link" id="back-phone">Back</button>
            </div>

            <div class="relative mt-2">
              <span class="icon-left">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
              </span>
              <input type="password" id="password" name="password" class="field pl-icon pr-16"
                     placeholder="Create a password (min. 8 characters)" required autocomplete="new-password" minlength="8">
              <button type="button" id="toggle-pw" class="pw-toggle text-xs">Show</button>
            </div>
            @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="relative mt-4">
              <span class="icon-left">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 7l-8 10-5-5"/>
                </svg>
              </span>
              <input type="password" id="password_confirmation" name="password_confirmation" class="field pl-icon pr-16"
                     placeholder="Confirm your password" required autocomplete="new-password" minlength="8">
              <button type="button" id="toggle-pw2" class="pw-toggle text-xs">Show</button>
            </div>

            <div class="mt-5"><button type="button" id="to-plan" class="btn btn-primary w-full">Continue</button></div>
          </div>

          <!-- STEP 5: SUBSCRIPTION -->
          <div id="step-plan" class="step">
            <div class="flex items-center justify-between">
              <label class="block text-sm font-semibold" style="color:var(--ink-900)">Choose your plan *</label>
              <button type="button" class="text-sm font-semibold link" id="back-passwords">Back</button>
            </div>

            <div class="plan-grid">
              <!-- Starter -->
              <label class="relative cursor-pointer">
                <input class="radio" type="radio" name="plan_choice" value="starter" required>
                <div class="plan-card">
                  <div class="plan-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 7l-8 10-5-5"/></svg>
                  </div>
                  <div class="plan-header">
                    <span class="plan-badge">Most popular</span>
                  </div>
                  <div class="plan-title">Starter</div>
                  <div class="plan-price">Free</div>
                  <ul class="plan-feats">
                    <li>• 50 leads/week</li>
                    <li>• Basic scripts</li>
                    <li>• Email support</li>
                  </ul>
                </div>
              </label>

              <!-- Professional -->
              <label class="relative cursor-pointer">
                <input class="radio" type="radio" name="plan_choice" value="paid" required>
                <div class="plan-card">
                  <div class="plan-check">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 7l-8 10-5-5"/></svg>
                  </div>
                  <div class="plan-header">
                    <span class="plan-badge">Best value</span>
                  </div>
                  <div class="plan-title">Professional</div>
                  <div class="plan-price">$79/month</div>
                  <ul class="plan-feats">
                    <li>• 200 leads/week</li>
                    <li>• Advanced scripts</li>
                    <li>• Priority support</li>
                  </ul>
                </div>
              </label>
            </div>

            <div class="flex items-start mt-4">
              <input type="checkbox" id="terms" name="terms" class="terms-check h-5 w-5 border-gray-300 rounded" required>
              <label for="terms" class="ml-3 block text-sm text-[#2F3E46]">
                I agree to the <a href="{{ url('/terms') }}" class="link">Terms &amp; Conditions</a>
              </label>
            </div>

            <button type="submit" class="w-full btn btn-primary mt-4">
              Create Account
              <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10 17l5-5-5-5"/><path d="M4 12h11"/>
              </svg>
            </button>
          </div>
        </form>

        <div class="my-4 text-center text-slate-700">
          <p>Already have an account?
            <a href="{{ route('login') }}" class="font-semibold link">Sign in</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  const sEmail = document.getElementById('step-email');
  const sName = document.getElementById('step-name');
  const sPhone = document.getElementById('step-phone');
  const sPw = document.getElementById('step-passwords');
  const sPlan = document.getElementById('step-plan');

  const email = document.getElementById('email');
  const nameI = document.getElementById('name');
  const phoneHidden = document.getElementById('phone');
  const phonePrefix = document.getElementById('phone_prefix');
  const phoneNumber = document.getElementById('phone_number');
  const pw1 = document.getElementById('password');
  const pw2 = document.getElementById('password_confirmation');

  const toName = document.getElementById('to-name');
  const backEmail = document.getElementById('back-email');
  const toPhone = document.getElementById('to-phone');
  const backName = document.getElementById('back-name');
  const toPw = document.getElementById('to-password');
  const backPhone = document.getElementById('back-phone');
  const toPlan = document.getElementById('to-plan');
  const backPw = document.getElementById('back-passwords');

  const toggle1 = document.getElementById('toggle-pw');
  const toggle2 = document.getElementById('toggle-pw2');

  const redirect = document.getElementById('redirect_to');
  const form = document.getElementById('register-form');

  function show(step){ [sEmail,sName,sPhone,sPw,sPlan].forEach(s=>s.classList.remove('active')); step.classList.add('active'); }
  const valid = (el)=>{ if(!el.checkValidity()){ el.reportValidity(); return false; } return true; };
  function mergePhone(){ phoneHidden.value = `${(phonePrefix.value||'').trim()}${(phoneNumber.value||'').trim()}`.replace(/\s+/g,''); }

  toName?.addEventListener('click', ()=>{ if(valid(email)) show(sName); setTimeout(()=>nameI?.focus(),120); });
  backEmail?.addEventListener('click', ()=>{ show(sEmail); setTimeout(()=>email?.focus(),120); });

  toPhone?.addEventListener('click', ()=>{ if(valid(nameI)) show(sPhone); setTimeout(()=>phonePrefix?.focus(),120); });
  backName?.addEventListener('click', ()=>{ show(sName); setTimeout(()=>nameI?.focus(),120); });

  toPw?.addEventListener('click', ()=>{
    if(!valid(phonePrefix) || !valid(phoneNumber)) return;
    mergePhone(); show(sPw); setTimeout(()=>pw1?.focus(),120);
  });
  backPhone?.addEventListener('click', ()=>{ show(sPhone); setTimeout(()=>phonePrefix?.focus(),120); });

  toPlan?.addEventListener('click', ()=>{
    if(!valid(pw1) || !valid(pw2)) return;
    if(pw1.value !== pw2.value){
      pw2.setCustomValidity("Passwords do not match."); pw2.reportValidity();
      pw2.addEventListener('input', ()=>pw2.setCustomValidity(''), { once:true });
      return;
    }
    show(sPlan);
  });
  backPw?.addEventListener('click', ()=>{ show(sPw); setTimeout(()=>pw1?.focus(),120); });

  // Enter key moves through steps, never submits early
  form.addEventListener('keydown', (e)=>{
    if(e.key !== 'Enter') return;
    const inEmail = sEmail.classList.contains('active');
    const inName = sName.classList.contains('active');
    const inPhone = sPhone.classList.contains('active');
    const inPw = sPw.classList.contains('active');
    if(inEmail){ e.preventDefault(); toName.click(); }
    else if(inName){ e.preventDefault(); toPhone.click(); }
    else if(inPhone){ e.preventDefault(); toPw.click(); }
    else if(inPw){ e.preventDefault(); toPlan.click(); }
  });

  function togglePw(input, btn){ if(input.type==='password'){ input.type='text'; btn.textContent='Hide'; } else { input.type='password'; btn.textContent='Show'; } input.focus(); }
  toggle1?.addEventListener('click', ()=>togglePw(pw1, toggle1));
  toggle2?.addEventListener('click', ()=>togglePw(pw2, toggle2));

  // Redirect based on plan_choice only (does not set user tier)
  function updateRedirect(){
    const planEl = document.querySelector('input[name="plan_choice"]:checked');
    if(planEl) redirect.value = (planEl.value === 'paid') ? '/book' : '/dashboard';
  }
  document.querySelectorAll('input[name="plan_choice"]').forEach(r=> r.addEventListener('change', updateRedirect));

  form.addEventListener('submit', (e)=>{
    mergePhone();
    const planEl = document.querySelector('input[name="plan_choice"]:checked');
    if(!planEl){ e.preventDefault(); show(sPlan); return; }
    updateRedirect();
  });

  @if ($errors->any())
    document.addEventListener('DOMContentLoaded', ()=>{
      @if ($errors->has('email')) show(sEmail);
      @elseif ($errors->has('name')) show(sName);
      @elseif ($errors->has('phone')) show(sPhone);
      @elseif ($errors->has('password')) show(sPw);
      @else show(sEmail);
      @endif
    });
  @else
    document.addEventListener('DOMContentLoaded', ()=> email && email.focus());
  @endif
})();
</script>
@endsection
