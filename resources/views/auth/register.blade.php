<x-guest-layout>
<style>
    /* Widen card for registration form */
    .auth-card { max-width: 650px !important; }

    /* Fix icon padding overrides from guest layout */
    .icon-pl { padding-left: 2.5rem !important; }
    .icon-pr { padding-right: 2.5rem !important; }

    /* Section styling */
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 1.5rem 0 1rem;
    }
    .section-title span {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--gold, #c8a951);
        white-space: nowrap;
    }
    .section-title::before, .section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(4, 9, 15, 0.08);
    }

    /* Password Requirements */
    .pw-req-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        margin-top: 0.5rem;
    }
    .pw-req {
        font-size: 0.7rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    .pw-req.met {
        color: #16a34a;
    }
    .pw-req.unmet {
        color: #ef4444;
    }
    .req-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #f1f5f9;
        transition: all 0.2s ease;
    }
    .pw-req.met .req-icon {
        background: rgba(22, 163, 74, 0.1);
        color: #16a34a;
    }
    .pw-req.unmet .req-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .info-box {
        background: rgba(200, 169, 81, 0.05);
        border-left: 3px solid var(--gold, #c8a951);
        padding: 0.75rem 1rem;
        border-radius: 0 0.5rem 0.5rem 0;
        font-size: 0.75rem;
        color: #475569;
        line-height: 1.5;
        margin: 1.5rem 0;
    }
</style>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="auth-alert auth-alert-error" role="alert">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Cadet Self-Registration</h2>
        <p class="text-sm text-slate-500 mt-1">Create your NROTC portal account</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
        @csrf

        {{-- ── Personal Information ── --}}
        <div class="section-title"><span>Personal Information</span></div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- First Name --}}
            <div>
                <label class="auth-label" for="first_name">First Name <span class="text-red-500">*</span></label>
                <input class="auth-input @error('first_name') is-error @enderror"
                       type="text" id="first_name" name="first_name"
                       value="{{ old('first_name') }}" placeholder="Given name"
                       autocomplete="given-name" autofocus required>
                @error('first_name')<div class="auth-field-error">{{ $message }}</div>@enderror
                <div class="auth-field-error" id="first-err" style="display:none;"></div>
            </div>

            {{-- Middle Name --}}
            <div>
                <label class="auth-label" for="middle_name">Middle Name <span class="text-slate-400 font-normal">(Optional)</span></label>
                <input class="auth-input @error('middle_name') is-error @enderror"
                       type="text" id="middle_name" name="middle_name"
                       value="{{ old('middle_name') }}" placeholder="Middle name"
                       autocomplete="additional-name">
                @error('middle_name')<div class="auth-field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Last Name --}}
            <div>
                <label class="auth-label" for="last_name">Last Name <span class="text-red-500">*</span></label>
                <input class="auth-input @error('last_name') is-error @enderror"
                       type="text" id="last_name" name="last_name"
                       value="{{ old('last_name') }}" placeholder="Surname"
                       autocomplete="family-name" required>
                @error('last_name')<div class="auth-field-error">{{ $message }}</div>@enderror
                <div class="auth-field-error" id="last-err" style="display:none;"></div>
            </div>

            {{-- Suffix --}}
            <div>
                <label class="auth-label" for="suffix">Suffix <span class="text-slate-400 font-normal">(Optional)</span></label>
                <div class="relative flex items-center">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <select class="auth-input icon-pl appearance-none bg-white cursor-pointer" name="suffix" id="suffix">
                        <option value="">— None —</option>
                        <option value="Jr." {{ old('suffix')=='Jr.' ? 'selected' : '' }}>Jr.</option>
                        <option value="Sr." {{ old('suffix')=='Sr.' ? 'selected' : '' }}>Sr.</option>
                        <option value="II"  {{ old('suffix')=='II'  ? 'selected' : '' }}>II</option>
                        <option value="III" {{ old('suffix')=='III' ? 'selected' : '' }}>III</option>
                        <option value="IV"  {{ old('suffix')=='IV'  ? 'selected' : '' }}>IV</option>
                        <option value="V"   {{ old('suffix')=='V'   ? 'selected' : '' }}>V</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Credentials ── --}}
        <div class="section-title"><span>Account Credentials</span></div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Student ID --}}
            <div>
                <label class="auth-label" for="student_id">Student ID <span class="text-red-500">*</span></label>
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <input class="auth-input icon-pl font-mono @error('student_id') is-error @enderror"
                           type="text" id="student_id" name="student_id"
                           value="{{ old('student_id') }}" placeholder="e.g. 2024-00001"
                           autocomplete="off" required>
                </div>
                <p class="text-[0.65rem] text-slate-400 mt-1">This will be your login ID.</p>
                @error('student_id')<div class="auth-field-error">{{ $message }}</div>@enderror
                <div class="auth-field-error" id="sid-err" style="display:none;"></div>
            </div>

            {{-- Email --}}
            <div>
                <label class="auth-label" for="email">Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <input class="auth-input icon-pl @error('email') is-error @enderror"
                           type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="you@csu.edu.ph"
                           autocomplete="email" required>
                </div>
                <p class="text-[0.65rem] text-slate-400 mt-1">We'll send a 6-digit OTP to this address.</p>
                @error('email')<div class="auth-field-error">{{ $message }}</div>@enderror
                <div class="auth-field-error" id="email-err" style="display:none;"></div>
            </div>
        </div>


        {{-- Inactive notice --}}
        <div class="info-box">
            <strong>Note:</strong> Your account will remain inactive until you submit your complete enrollment form and an officer validates your application.
        </div>

        <button type="submit" class="auth-submit">
            Continue
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-slate-800 transition-colors font-medium">
            Already have an account? <span style="color: var(--gold, #c8a951);">Sign in here</span>
        </a>
    </div>

    <script>
    (function () {
        var ln = document.getElementById('last_name'),
            fn = document.getElementById('first_name'),
            s  = document.getElementById('student_id'),
            em = document.getElementById('email');
        var lE=document.getElementById('last-err'), fE=document.getElementById('first-err'),
            sE=document.getElementById('sid-err'),  eE=document.getElementById('email-err');

        function show(el,err,msg){ el.classList.add('is-error'); err.textContent=msg; err.style.display='block'; }
        function clear(el,err)   { el.classList.remove('is-error'); err.textContent=''; err.style.display='none'; }

        function vLast()    { var v=ln.value.trim(); if(!v){show(ln,lE,'Last name is required.'); return false;} clear(ln,lE); return true; }
        function vFirst()   { var v=fn.value.trim(); if(!v){show(fn,fE,'First name is required.'); return false;} clear(fn,fE); return true; }
        function vSid()     { var v=s.value.trim();  if(!v){show(s,sE,'Student ID is required.'); return false;} if(v.length>50){show(s,sE,'Max 50 characters.'); return false;} clear(s,sE); return true; }
        function vEmail()   { var v=em.value.trim(); if(!v){show(em,eE,'Email is required.'); return false;} if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)){show(em,eE,'Enter a valid email.'); return false;} clear(em,eE); return true; }

        ln.addEventListener('blur',vLast);   ln.addEventListener('input',function(){ if(ln.classList.contains('is-error')) vLast(); });
        fn.addEventListener('blur',vFirst);  fn.addEventListener('input',function(){ if(fn.classList.contains('is-error')) vFirst(); });
        s.addEventListener('blur',vSid);     s.addEventListener('input',function(){ if(s.classList.contains('is-error'))   vSid(); });
        em.addEventListener('blur',vEmail);  em.addEventListener('input',function(){ if(em.classList.contains('is-error')) vEmail(); });

        document.getElementById('registerForm').addEventListener('submit',function(ev){
            var r=[vLast(),vFirst(),vSid(),vEmail()];
            if(r.includes(false)){ ev.preventDefault(); [ln,fn,s,em][r.indexOf(false)].focus(); }
        });
    })();
    </script>

</x-guest-layout>
