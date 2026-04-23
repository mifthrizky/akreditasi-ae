<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem IABEE</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            background: #0a1628;
        }

        .container-auth {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* LEFT SIDE */
        .auth-left {
            position: relative;
            padding: 80px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;

            background:
                linear-gradient(135deg, rgba(10, 22, 40, 0.95), rgba(15, 36, 68, 0.95)),
                #0a1628;
        }

        /* GRID */
        .auth-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* GLOW */
        .auth-left::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            top: -150px;
            left: -150px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.35), transparent 70%);
            filter: blur(120px);
        }

        .glow-bottom {
            position: absolute;
            width: 500px;
            height: 500px;
            bottom: -150px;
            right: -150px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.25), transparent 70%);
            filter: blur(120px);
        }

        .auth-left h1 {
            font-size: 42px;
            font-weight: 900;
            line-height: 1.2;
        }

        .auth-left p {
            margin-top: 16px;
            color: #94a3b8;
            max-width: 420px;
        }

        .logo {
            height: 42px;
            margin-bottom: 40px;
        }

        /* RIGHT SIDE */
        .auth-right {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
        }

        /* CARD */
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 42px;
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .auth-title {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .auth-sub {
            font-size: 14px;
            color: #64748b;
            margin-top: 6px;
        }

        .auth-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .auth-input {
            width: 100%;
            padding: 13px 14px;
            margin-top: 6px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
        }

        .auth-input:focus {
            background: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            border-radius: 12px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: 0.2s;
        }

        .auth-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #64748b;
        }

        @media (max-width: 900px) {
            .container-auth {
                grid-template-columns: 1fr;
            }

            .auth-left {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container-auth">

        <!-- LEFT -->
        <div class="auth-left">

            <div>

                <img src="{{ asset('images/polman.png') }}" class="logo" alt="Logo Polman">

                <div class="flex flex-col justify-center h-full">
                    <h1 class="text-white font-bold text-5xl leading-tight mb-4">
                        Sistem Pemeriksa <br>
                        <span class="text-blue-400">Panduan Kurikulum</span>
                    </h1>

                    <p class="text-gray-300 text-lg max-w-md">
                        Platform profesional untuk pengelolaan persiapan akreditasi internasional
                        secara efisien, terstruktur, dan modern.
                    </p>
                </div>
            </div>

            <p style="font-size:12px; color:#64748b;">
                © {{ date('Y') }} Polman Bandung
            </p>

            <div class="glow-bottom"></div>
        </div>

        <!-- RIGHT -->
        <div class="auth-right">

            <div class="auth-card">

                <h2 class="auth-title">Masuk ke Sistem</h2>
                <p class="auth-sub">Gunakan akun resmi Anda</p>

                @if ($errors->any())
                    <div
                        style="background:#fee2e2; padding:10px; border-radius:10px; margin-top:15px; font-size:13px; color:#b91c1c;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" style="margin-top:20px;">
                    @csrf

                    <div style="margin-bottom:15px;">
                        <label class="auth-label">Email</label>
                        <input type="email" name="email" required class="auth-input">
                    </div>

                    <div style="margin-bottom:15px;">
                        <label class="auth-label">Password</label>
                        <input type="password" name="password" required class="auth-input">
                    </div>

                    <button type="submit" class="auth-btn">
                        Masuk ke Dashboard
                    </button>
                </form>

                <div class="auth-footer">
                    <a href="/" style="color:#3b82f6;">Kembali ke beranda</a>
                </div>

            </div>

        </div>

    </div>

</body>

</html>
