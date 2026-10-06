<x-guest-layout>

    @if ($errors->any())
        <div class="auth-alert auth-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" id="resetForm" novalidate>
        @csrf

        {{-- Email (verified by code, read-only) --}}
        <div style="margin-bottom: 1.125rem;">
            <label class="auth-label" for="email">Email Address</label>
            <input class="auth-input"
                   type="email" id="email"
                   value="{{ $email }}"
                   autocomplete="username"
                   readonly>
            <div class="auth-field-error" id="email-client-error" role="alert" style="display:none;"></div>
        </div>

        {{-- New Password --}}
        <div style="margin-bottom: 1.125rem;">
            <label class="auth-label" for="password">New Password</label>
            <div style="position: relative;">
                <input class="auth-input @error('password') is-error @enderror"
                       type="password" id="password" name="password"
                       placeholder="Minimum 8 characters"
                       autocomplete="new-password"
                       required>
                <button type="button" class="auth-pw-toggle" onclick="togglePw('password', this)" aria-label="Toggle password visibility">
                    <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            @error('password')
                <div class="auth-field-error" role="alert">{{ $message }}</div>
            @enderror
            <div class="auth-field-error" id="password-client-error" role="alert" style="display:none;"></div>
        </div>

        {{-- Confirm Password --}}
        <div style="margin-bottom: 1.25rem;">
            <label class="auth-label" for="password_confirmation">Confirm New Password</label>
            <div style="position: relative;">
                <input class="auth-input"
                       type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Repeat new password"
                       autocomplete="new-password"
                       required>
                <button type="button" class="auth-pw-toggle" onclick="togglePw('password_confirmation', this)" aria-label="Toggle confirm password visibility">
                    <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <div class="auth-field-error" id="confirm-client-error" role="alert" style="display:none;"></div>
        </div>

        <button type="submit" class="auth-submit">Reset Password</button>
    </form>

    <script>
    function togglePw(id, btn) {
        var inp = document.getElementById(id);
        var open = btn.querySelector('.eye-open');
        var closed = btn.querySelector('.eye-closed');
        if (inp.type === 'password') {
            inp.type = 'text';
            open.style.display = 'none';
            closed.style.display = 'block';
        } else {
            inp.type = 'password';
            open.style.display = 'block';
            closed.style.display = 'none';
        }
    }

    (function () {
        var emailInput = document.getElementById('email');
        var pwInput = document.getElementById('password');
        var confirmInput = document.getElementById('password_confirmation');
        var emailErr = document.getElementById('email-client-error');
        var pwErr = document.getElementById('password-client-error');
        var confirmErr = document.getElementById('confirm-client-error');
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        function showErr(input, el, msg) { input.classList.add('is-error'); el.textContent = msg; el.style.display = 'block'; }
        function clearErr(input, el) { input.classList.remove('is-error'); el.textContent = ''; el.style.display = 'none'; }

        function validateEmail() {
            var v = emailInput.value.trim();
            if (!v) { showErr(emailInput, emailErr, 'Email address is required.'); return false; }
            if (!emailPattern.test(v)) { showErr(emailInput, emailErr, 'Please enter a valid email address.'); return false; }
            clearErr(emailInput, emailErr); return true;
        }
        function validatePw() {
            if (!pwInput.value) { showErr(pwInput, pwErr, 'Password is required.'); return false; }
            if (pwInput.value.length < 8) { showErr(pwInput, pwErr, 'Password must be at least 8 characters.'); return false; }
            clearErr(pwInput, pwErr); return true;
        }
        function validateConfirm() {
            if (!confirmInput.value) { showErr(confirmInput, confirmErr, 'Please confirm your password.'); return false; }
            if (confirmInput.value !== pwInput.value) { showErr(confirmInput, confirmErr, 'Passwords do not match.'); return false; }
            clearErr(confirmInput, confirmErr); return true;
        }

        emailInput.addEventListener('blur', validateEmail);
        emailInput.addEventListener('input', function () { if (emailInput.classList.contains('is-error')) validateEmail(); });
        pwInput.addEventListener('blur', validatePw);
        pwInput.addEventListener('input', function () { if (pwInput.classList.contains('is-error')) validatePw(); });
        confirmInput.addEventListener('blur', validateConfirm);
        confirmInput.addEventListener('input', function () { if (confirmInput.classList.contains('is-error')) validateConfirm(); });

        document.getElementById('resetForm').addEventListener('submit', function (e) {
            var a = validateEmail(), b = validatePw(), c = validateConfirm();
            if (!a || !b || !c) {
                e.preventDefault();
                if (!a) emailInput.focus();
                else if (!b) pwInput.focus();
                else confirmInput.focus();
            }
        });
    })();
    </script>

</x-guest-layout>
