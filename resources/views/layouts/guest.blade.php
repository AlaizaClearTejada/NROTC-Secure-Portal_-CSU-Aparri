<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'CCJE ROTC') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body.auth-page {
                font-family: 'Inter', system-ui, sans-serif;
                background: linear-gradient(160deg, #1a0606 0%, #0f0404 50%, #150808 100%);
                min-height: 100vh;
            }

            .auth-card {
                width: 100%;
                max-width: 420px;
                background: #ffffff;
                border-radius: 12px;
                padding: 2.5rem 2rem 2rem;
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15), 0 1px 3px rgba(0, 0, 0, 0.08);
            }

            .auth-header {
                text-align: center;
                margin-bottom: 2rem;
            }
            .auth-header-logo {
                width: 64px;
                height: 64px;
                margin: 0 auto 1rem;
                object-fit: contain;
            }
            .auth-header h1 {
                font-size: 1.25rem;
                font-weight: 700;
                color: #111827;
                margin: 0 0 .25rem;
                letter-spacing: -0.01em;
            }
            .auth-header p {
                font-size: .8rem;
                color: #6b7280;
                margin: 0;
            }

            .auth-label {
                display: block;
                font-size: .8rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: .375rem;
            }
            .auth-input {
                width: 100%;
                padding: .625rem .875rem;
                font-size: .9rem;
                color: #111827;
                background: #ffffff;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                outline: none;
                transition: border-color .15s, box-shadow .15s;
            }
            .auth-input::placeholder {
                color: #9ca3af;
            }
            .auth-input:focus {
                border-color: #800000;
                box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.1);
            }
            .auth-input.is-error {
                border-color: #dc2626;
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
            }

            .auth-field-error {
                font-size: .75rem;
                color: #dc2626;
                margin-top: .25rem;
            }

            .auth-alert {
                display: flex;
                align-items: center;
                gap: .5rem;
                padding: .625rem .875rem;
                border-radius: 8px;
                font-size: .8rem;
                line-height: 1.5;
                margin-bottom: 1rem;
            }
            .auth-alert-success {
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                color: #166534;
            }
            .auth-alert-error {
                background: #fef2f2;
                border: 1px solid #fecaca;
                color: #991b1b;
            }

            .auth-extras {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1.25rem;
            }
            .auth-check-label {
                display: flex;
                align-items: center;
                gap: .5rem;
                font-size: .8rem;
                color: #374151;
                cursor: pointer;
                user-select: none;
            }
            .auth-check-label input[type="checkbox"] {
                accent-color: #800000;
                width: 15px;
                height: 15px;
                min-width: 15px;
                cursor: pointer;
                vertical-align: middle;
                margin: 0;
                flex-shrink: 0;
            }
            .auth-forgot {
                font-size: .8rem;
                color: #800000;
                text-decoration: none;
                font-weight: 500;
                transition: color .15s;
            }
            .auth-forgot:hover {
                color: #5a0000;
                text-decoration: underline;
            }

            .auth-submit {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding: .7rem 1.25rem;
                font-size: .9rem;
                font-weight: 600;
                color: #ffffff;
                background: #800000;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background .15s;
            }
            .auth-submit:hover {
                background: #6b0000;
            }
            .auth-submit:active {
                background: #5a0000;
            }

            .auth-pw-toggle {
                position: absolute;
                right: .75rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: #9ca3af;
                font-size: 1rem;
                padding: 0;
                line-height: 1;
                transition: color .15s;
            }
            .auth-pw-toggle:hover {
                color: #6b7280;
            }

            .auth-footer {
                margin-top: 1.25rem;
                text-align: center;
                font-size: .7rem;
                color: #9ca3af;
            }

            @media (max-width: 480px) {
                .auth-card {
                    padding: 2rem 1.25rem 1.5rem;
                    border-radius: 8px;
                }
            }
        </style>
    </head>
    <body class="auth-page antialiased">
        @include('partials.page-loader')

        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="auth-card">
                {{-- Header --}}
                <div class="auth-header">
                    <img src="{{ asset('133rd NROTC_logo.jpg') }}" alt="133rd NROTC Logo" class="auth-header-logo" style="border-radius:50%;object-fit:cover;">
                    <h1>133rd NROTC Information System</h1>
                    <p>CSU Aparri &middot; 133rd NROTC Portal</p>
                </div>

                {{ $slot }}

                <p class="auth-footer">
                    &copy; {{ date('Y') }} CSU Aparri 133rd NROTC &middot; All sessions are monitored
                </p>
            </div>
        </div>

    </body>
</html>
