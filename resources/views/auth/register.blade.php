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
                <p class="text-[0.65rem] text-slate-400 mt-1">Used for password recovery.</p>
                @error('email')<div class="auth-field-error">{{ $message }}</div>@enderror
                <div class="auth-field-error" id="email-err" style="display:none;"></div>
            </div>
        </div>

        {{-- ── Security ── --}}
        <div class="section-title"><span>Security</span></div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Password --}}
            <div>
                <label class="auth-label" for="password">Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <input class="auth-input icon-pl icon-pr @error('password') is-error @enderror"
                           type="password" id="password" name="password"
                           placeholder="Create password" autocomplete="new-password" required>
                    <button type="button" class="auth-pw-toggle" onclick="togglePw('password',this)" aria-label="Toggle">
                        <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                @error('password')<div class="auth-field-error">{{ $message }}</div>@enderror
                <div class="auth-field-error" id="pw-err" style="display:none;"></div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="auth-label" for="password_confirmation">Confirm Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <input class="auth-input icon-pl icon-pr"
                           type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Re-enter password" autocomplete="new-password" required>
                    <button type="button" class="auth-pw-toggle" onclick="togglePw('password_confirmation',this)" aria-label="Toggle">
                        <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                <div class="auth-field-error" id="confirm-err" style="display:none;"></div>
            </div>
        </div>

        {{-- Password requirements (live) --}}
        <div class="pw-req-grid">
            <div class="pw-req" data-rule="length">
                <div class="req-icon"><svg width="8" height="8" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" stroke="currentColor" stroke-width="1.5"/></svg></div>
                <span>Min. 8 characters</span>
            </div>
            <div class="pw-req" data-rule="upper">
                <div class="req-icon"><svg width="8" height="8" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" stroke="currentColor" stroke-width="1.5"/></svg></div>
                <span>Uppercase letter</span>
            </div>
            <div class="pw-req" data-rule="lower">
                <div class="req-icon"><svg width="8" height="8" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" stroke="currentColor" stroke-width="1.5"/></svg></div>
                <span>Lowercase letter</span>
            </div>
            <div class="pw-req" data-rule="number">
                <div class="req-icon"><svg width="8" height="8" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" stroke="currentColor" stroke-width="1.5"/></svg></div>
                <span>At least one number</span>
            </div>
        </div>

        {{-- Inactive notice --}}
        <div class="info-box">
            <strong>Note:</strong> Your account will remain inactive until you submit your complete enrollment form and an officer validates your application.
        </div>

        <button type="submit" class="auth-submit">
            Create Account
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-slate-800 transition-colors font-medium">
            Already have an account? <span style="color: var(--gold, #c8a951);">Sign in here</span>
        </a>
    </div>

    <script>
    function togglePw(id, btn) {
        var inp = document.getElementById(id);
        var open = btn.querySelector('.eye-open'), closed = btn.querySelector('.eye-closed');
        if (inp.type === 'password') { inp.type='text'; open.style.display='none'; closed.style.display='block'; }
        else { inp.type='password'; open.style.display='block'; closed.style.display='none'; }
    }
    (function () {
        var ln = document.getElementById('last_name'),
            fn = document.getElementById('first_name'),
            s  = document.getElementById('student_id'),
            em = document.getElementById('email'),
            pw = document.getElementById('password'),
            cf = document.getElementById('password_confirmation');
        var lE=document.getElementById('last-err'), fE=document.getElementById('first-err'),
            sE=document.getElementById('sid-err'),  eE=document.getElementById('email-err'),
            pE=document.getElementById('pw-err'),   cE=document.getElementById('confirm-err');

        function show(el,err,msg){ el.classList.add('is-error'); err.textContent=msg; err.style.display='block'; }
        function clear(el,err)   { el.classList.remove('is-error'); err.textContent=''; err.style.display='none'; }

        function vLast()    { var v=ln.value.trim(); if(!v){show(ln,lE,'Last name is required.'); return false;} clear(ln,lE); return true; }
        function vFirst()   { var v=fn.value.trim(); if(!v){show(fn,fE,'First name is required.'); return false;} clear(fn,fE); return true; }
        function vSid()     { var v=s.value.trim();  if(!v){show(s,sE,'Student ID is required.'); return false;} if(v.length>50){show(s,sE,'Max 50 characters.'); return false;} clear(s,sE); return true; }
        function vEmail()   { var v=em.value.trim(); if(!v){show(em,eE,'Email is required.'); return false;} if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)){show(em,eE,'Enter a valid email.'); return false;} clear(em,eE); return true; }
        function vPw()      { var v=pw.value; if(!v){show(pw,pE,'Password is required.'); return false;} if(v.length<8||!/[A-Z]/.test(v)||!/[a-z]/.test(v)||!/[0-9]/.test(v)){show(pw,pE,'Does not meet requirements.'); return false;} clear(pw,pE); return true; }
        function vConfirm() { var v=cf.value; if(!v){show(cf,cE,'Please confirm.'); return false;} if(v!==pw.value){show(cf,cE,'Passwords do not match.'); return false;} clear(cf,cE); return true; }

        // Live password requirements
        var svgOk='<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        var svgNo='<svg width="8" height="8" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" stroke="currentColor" stroke-width="1.5"/></svg>';
        var rules={
            length: function(v){return v.length>=8;},
            upper:  function(v){return /[A-Z]/.test(v);},
            lower:  function(v){return /[a-z]/.test(v);},
            number: function(v){return /[0-9]/.test(v);}
        };
        pw.addEventListener('input',function(){
            var v=pw.value;
            document.querySelectorAll('.pw-req').forEach(function(el){
                var ok=rules[el.getAttribute('data-rule')](v);
                var icon = el.querySelector('.req-icon');
                if(ok) {
                    el.classList.add('met');
                    el.classList.remove('unmet');
                    icon.innerHTML = svgOk;
                } else if(v.length > 0) {
                    el.classList.add('unmet');
                    el.classList.remove('met');
                    icon.innerHTML = svgNo;
                } else {
                    el.classList.remove('met', 'unmet');
                    icon.innerHTML = svgNo;
                }
            });
        });

        ln.addEventListener('blur',vLast);   ln.addEventListener('input',function(){ if(ln.classList.contains('is-error')) vLast(); });
        fn.addEventListener('blur',vFirst);  fn.addEventListener('input',function(){ if(fn.classList.contains('is-error')) vFirst(); });
        s.addEventListener('blur',vSid);     s.addEventListener('input',function(){ if(s.classList.contains('is-error'))   vSid(); });
        em.addEventListener('blur',vEmail);  em.addEventListener('input',function(){ if(em.classList.contains('is-error')) vEmail(); });
        pw.addEventListener('blur',vPw);     pw.addEventListener('input',function(){ if(pw.classList.contains('is-error')) vPw(); if(cf.value&&cf.classList.contains('is-error')) vConfirm(); });
        cf.addEventListener('blur',vConfirm);cf.addEventListener('input',function(){ if(cf.classList.contains('is-error')) vConfirm(); });

        document.getElementById('registerForm').addEventListener('submit',function(ev){
            var r=[vLast(),vFirst(),vSid(),vEmail(),vPw(),vConfirm()];
            if(r.includes(false)){ ev.preventDefault(); [ln,fn,s,em,pw,cf][r.indexOf(false)].focus(); }
        });
    })();
    </script>

</x-guest-layout>
