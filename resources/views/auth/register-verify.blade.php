<x-guest-layout>

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

    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-slate-800">Verify your email</h2>
        <p class="text-sm text-slate-500 mt-2">
            Enter the 6-digit OTP sent to <span class="font-semibold text-slate-700">{{ $email }}</span>.
            The OTP expires in 10 minutes.
        </p>
    </div>

    <form method="POST" action="{{ route('register.verify.store') }}" id="registerVerifyForm" novalidate>
        @csrf

        <div style="margin-bottom: 1.25rem;">
            <label class="auth-label" for="code">OTP</label>
            <input class="auth-input @error('code') is-error @enderror"
                   type="text" id="code" name="code"
                   inputmode="numeric" autocomplete="one-time-code"
                   maxlength="6" placeholder="000000"
                   style="text-align:center; letter-spacing:.5em; font-size:1.25rem;"
                   autofocus required>
        </div>

        <button type="submit" class="auth-submit">Continue</button>
    </form>

    <div style="margin-top: 1.25rem; text-align: center;">
        <form method="POST" action="{{ route('register.verify.resend') }}" style="display:inline;">
            @csrf
            <button type="submit" class="auth-forgot" style="font-size: .8rem; background:none; border:none; cursor:pointer;">
                Resend OTP
            </button>
        </form>
        <span style="color:#cbd5e1; margin: 0 .5rem;">|</span>
        <a href="{{ route('register') }}" class="auth-forgot" style="font-size: .8rem;">Change details</a>
    </div>

</x-guest-layout>
