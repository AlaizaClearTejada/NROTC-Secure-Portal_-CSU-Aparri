<x-guest-layout>
<style>
    .icon-pl { padding-left: 2.5rem !important; }
    .icon-pr { padding-right: 2.5rem !important; }
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
    .pw-req { font-size: 0.7rem; color: #9ca3af; display: flex; align-items: center; gap: 0.4rem; transition: all 0.2s ease; font-weight: 500; }
    .pw-req.met { color: #16a34a; }
    .pw-req.unmet { color: #ef4444; }
    .req-icon { display: flex; align-items: center; justify-content: center; width: 14px; height: 14px; border-radius: 50%; background: #f1f5f9; transition: all 0.2s ease; }
    .pw-req.met .req-icon { background: rgba(22, 163, 74, 0.1); color: #16a34a; }
    .pw-req.unmet .req-icon { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
</style>

    @if ($errors->any())
        <div class="auth-alert auth-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Create your password</h2>
        <p class="text-sm text-slate-500 mt-2">
            Your email <span class="font-semibold text-slate-700">{{ $email }}</span> is verified.
        </p>
    </div>

    <form method="POST" action="{{ route('register.password.store') }}" id="registerPasswordForm" novalidate>
        @csrf

        <div style="margin-bottom: 1.125rem;">
            <label class="auth-label" for="password">Password <span class="text-red-500">*</span></label>
            <div style="position: relative;">
                <input class="auth-input icon-pr @error('password') is-error @enderror"
                       type="password" id="password" name="password"
                       placeholder="Create password" autocomplete="new-password" autofocus required>
                <button type="button" class="auth-pw-toggle" onclick="togglePw('password', this)" aria-label="Toggle password visibility">
                    <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            @error('password')
                <div class="auth-field-error" role="alert">{{ $message }}</div>
            @enderror
            <div class="auth-field-error" id="pw-err" role="alert" style="display:none;"></div>

            <div class="pw-req-grid">
                <div class="pw-req" data-rule="length"><div class="req-icon"></div><span>Min. 8 characters</span></div>
                <div class="pw-req" data-rule="upper"><div class="req-icon"></div><span>Uppercase letter</span></div>
                <div class="pw-req" data-rule="lower"><div class="req-icon"></div><span>Lowercase letter</span></div>
                <div class="pw-req" data-rule="number"><div class="req-icon"></div><span>At least one number</span></div>
            </div>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label class="auth-label" for="password_confirmation">Confirm password <span class="text-red-500">*</span></label>
            <div style="position: relative;">
                <input class="auth-input icon-pr"
                       type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Re-enter password" autocomplete="new-password" required>
                <button type="button" class="auth-pw-toggle" onclick="togglePw('password_confirmation', this)" aria-label="Toggle confirm password visibility">
                    <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <div class="auth-field-error" id="confirm-err" role="alert" style="display:none;"></div>
        </div>

        <button type="submit" class="auth-submit">Create Account</button>
    </form>

    <div style="margin-top: 1.25rem; text-align: center;">
        <a href="{{ route('login') }}" class="auth-forgot" style="font-size: .8rem;">Already have an account? Sign in</a>
    </div>

    <script>
    function togglePw(id, btn) {
        var inp = document.getElementById(id);
        var open = btn.querySelector('.eye-open'), closed = btn.querySelector('.eye-closed');
        if (inp.type === 'password') { inp.type = 'text'; open.style.display = 'none'; closed.style.display = 'block'; }
        else { inp.type = 'password'; open.style.display = 'block'; closed.style.display = 'none'; }
    }
    (function () {
        var pw = document.getElementById('password');
        var cf = document.getElementById('password_confirmation');
        var pwErr = document.getElementById('pw-err');
        var cfErr = document.getElementById('confirm-err');
        var form = document.getElementById('registerPasswordForm');
        var svgOk = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        var svgNo = '<svg width="8" height="8" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4.5" stroke="currentColor" stroke-width="1.5"/></svg>';
        var rules = {
            length: function (v) { return v.length >= 8; },
            upper: function (v) { return /[A-Z]/.test(v); },
            lower: function (v) { return /[a-z]/.test(v); },
            number: function (v) { return /[0-9]/.test(v); }
        };

        function show(el, err, msg) { el.classList.add('is-error'); err.textContent = msg; err.style.display = 'block'; }
        function clear(el, err) { el.classList.remove('is-error'); err.textContent = ''; err.style.display = 'none'; }

        function validPassword() {
            var v = pw.value;
            if (!v) { show(pw, pwErr, 'Password is required.'); return false; }
            if (v.length < 8 || !/[A-Z]/.test(v) || !/[a-z]/.test(v) || !/[0-9]/.test(v)) { show(pw, pwErr, 'Password does not meet the requirements.'); return false; }
            clear(pw, pwErr); return true;
        }
        function validConfirm() {
            var v = cf.value;
            if (!v) { show(cf, cfErr, 'Please confirm your password.'); return false; }
            if (v !== pw.value) { show(cf, cfErr, 'Passwords do not match.'); return false; }
            clear(cf, cfErr); return true;
        }

        pw.addEventListener('input', function () {
            var v = pw.value;
            document.querySelectorAll('.pw-req').forEach(function (el) {
                var icon = el.querySelector('.req-icon');
                var ok = rules[el.getAttribute('data-rule')](v);
                el.classList.toggle('met', ok);
                el.classList.toggle('unmet', !ok && v.length > 0);
                icon.innerHTML = ok ? svgOk : svgNo;
            });
            if (pw.classList.contains('is-error')) validPassword();
        });
        pw.addEventListener('blur', validPassword);
        cf.addEventListener('blur', validConfirm);
        cf.addEventListener('input', function () { if (cf.classList.contains('is-error')) validConfirm(); });

        form.addEventListener('submit', function (ev) {
            var ok = [validPassword(), validConfirm()];
            if (ok.includes(false)) { ev.preventDefault(); [pw, cf][ok.indexOf(false)].focus(); }
        });
    })();
    </script>

</x-guest-layout>
