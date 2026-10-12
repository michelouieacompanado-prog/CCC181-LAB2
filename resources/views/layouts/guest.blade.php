<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background-color: #0f172a !important;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            /* Override Tailwind defaults for dark theme */
            .auth-card {
                background-color: #1e293b;
                border: 1px solid #334155;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
                padding: 2rem;
                width: 100%;
                max-width: 420px;
            }

            .auth-wrapper {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            .auth-logo {
                margin-bottom: 1.5rem;
                text-align: center;
            }

            .auth-logo-text {
                color: #f8fafc;
                font-size: 1.4rem;
                font-weight: 700;
                letter-spacing: -0.02em;
            }

            .auth-logo-sub {
                color: #64748b;
                font-size: 0.8rem;
                margin-top: 2px;
            }

            /* Override Breeze input/label colors */
            input[type="text"],
            input[type="email"],
            input[type="password"] {
                background-color: #0f172a !important;
                border: 1px solid #334155 !important;
                color: #f8fafc !important;
                border-radius: 6px !important;
            }

            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="password"]:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 2px rgba(59,130,246,0.2) !important;
                outline: none !important;
            }

            label {
                color: #94a3b8 !important;
                font-size: 13px !important;
            }

            /* Primary button */
            button[type="submit"],
            .btn-primary {
                background-color: #3b82f6 !important;
                color: #fff !important;
                border: none !important;
                border-radius: 6px !important;
                padding: 8px 20px !important;
                font-weight: 500 !important;
                cursor: pointer !important;
                transition: background 0.2s !important;
            }

            button[type="submit"]:hover {
                background-color: #2563eb !important;
            }

            /* Links */
            a {
                color: #60a5fa !important;
            }

            a:hover {
                color: #93c5fd !important;
            }

            /* Error messages */
            .text-red-600, [class*="text-red"] {
                color: #f87171 !important;
            }

            /* Checkbox */
            input[type="checkbox"] {
                accent-color: #3b82f6;
            }

            span.text-gray-600, span.text-gray-400 {
                color: #94a3b8 !important;
            }
        </style>
    </head>
    <body>
        <div class="auth-wrapper">
            {{-- Logo / Branding --}}
            <div class="auth-logo">
                <a href="/">
                    <div class="auth-logo-text">🛒 CCC181 Product Manager</div>
                    <div class="auth-logo-sub">Application Development &amp; Emerging Technologies</div>
                </a>
            </div>

            {{-- Auth Card --}}
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
