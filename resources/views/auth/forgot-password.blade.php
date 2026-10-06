<x-guest-layout>

    <p style="font-size: .85rem; color: #6b7280; line-height: 1.55; margin-bottom: 1.25rem;">
        Enter your registered email address and we'll send you a 6-digit OTP to reset your password.
    </p>

    @if (session('status'))
        <div class="auth-alert auth-alert-success" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="auth-alert auth-alert-error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgotForm" novalidate>
        @csrf

        <div style="margin-bottom: 1.25rem;">
            <label class="auth-label" for="email">Email Address</label>
            <input class="auth-input @error('email') is-error @enderror"
                   type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@csuaparri.edu.ph"
                   autofocus required>
            @error('email')
                <div class="auth-field-error" role="alert">{{ $message }}</div>
            @enderror
            <div class="auth-field-error" id="email-client-error" role="alert" style="display:none;"></div>
        </div>

        <button type="submit" class="auth-submit">Send OTP</button>

        <div style="margin-top: 1.25rem; text-align: center;">
            <a href="{{ route('login') }}" class="auth-forgot" style="font-size: .8rem;">
                &larr; Back to Sign In
            </a>
        </div>
    </form>

    <script>
    (function () {
        var emailInput = document.getElementById('email');
        var emailErr = document.getElementById('email-client-error');
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        function validateEmail() {
            var val = emailInput.value.trim();
            if (!val) { showErr('Email address is required.'); return false; }
            if (!emailPattern.test(val)) { showErr('Please enter a valid email address.'); return false; }
            clearErr(); return true;
        }
        function showErr(msg) { emailInput.classList.add('is-error'); emailErr.textContent = msg; emailErr.style.display = 'block'; }
        function clearErr() { emailInput.classList.remove('is-error'); emailErr.textContent = ''; emailErr.style.display = 'none'; }

        emailInput.addEventListener('blur', validateEmail);
        emailInput.addEventListener('input', function () { if (emailInput.classList.contains('is-error')) validateEmail(); });
        document.getElementById('forgotForm').addEventListener('submit', function (e) {
            if (!validateEmail()) { e.preventDefault(); emailInput.focus(); }
        });
    })();
    </script>

</x-guest-layout>
