<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pemeriksa Panduan IABEE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" href="/favicon.ico">
    <style>
        html,
        body {
            font-family: 'DM Sans', sans-serif;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* Edge/IE */
            background-color: #0f172a;
            overscroll-behavior: none;
        }

        html::-webkit-scrollbar {
            display: none;
        }


        /* Hide scrollbar for Chrome/Safari */
        body::-webkit-scrollbar {
            display: none;
        }

        .font-display {
            font-family: 'DM Sans', sans-serif;
        }

        /* Hero asymmetric Z-pattern */
        .hero-wrapper {
            position: relative;
            min-height: 100dvh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            overflow: hidden;
            background-color: #0f172a;
        }

        .hero-left {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 6rem 4rem 6rem 5rem;
            background-color: #0f172a;
            grid-column: 1 / 2;
            grid-row: 1;
        }

        .hero-left::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image:
                linear-gradient(rgba(139, 92, 246, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139, 92, 246, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            -webkit-mask-image: radial-gradient(circle at top left, black 0%, transparent 70%);
            mask-image: radial-gradient(circle at top left, black 0%, transparent 70%);
        }

        .hero-left::after {
            display: none;
        }

        .hero-glow-bottom {
            position: absolute;
            width: 600px;
            height: 600px;
            bottom: -200px;
            right: -200px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.2), transparent 70%);
            filter: blur(140px);
            z-index: 0;
            pointer-events: none;
        }

        /* Right panel */
        .hero-right {
            position: relative;
            z-index: 1;
            background-image: url('https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=1400&q=80');
            background-size: cover;
            background-position: center;
            grid-column: 2 / 3;
            grid-row: 1;
            overflow: hidden;
            transform: translateX(60px) translateY(80px);
            border-radius: 24px;
            margin: 3rem 2rem;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
        }

        /* Gradient mask */
        .hero-right::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(15, 23, 42, 0.6) 0%,
                    rgba(15, 23, 42, 0.3) 50%,
                    rgba(15, 23, 42, 0.1) 100%);
            border-radius: 24px;
        }

        /* Accent line */
        .accent-line {
            width: 48px;
            height: 3px;
            background: linear-gradient(90deg, #7C3AED, #8B5CF6);
            border-radius: 2px;
        }

        /* Stat badge */
        .stat-badge {
            border: 1px solid rgba(139, 92, 246, 0.4);
            background: rgba(139, 92, 246, 0.08);
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);
            transition: all 0.3s ease;
        }

        .stat-badge:hover {
            background: rgba(139, 92, 246, 0.12);
            border-color: rgba(139, 92, 246, 0.6);
        }

        /* Floating card on hero right */
        .hero-float-card {
            position: absolute;
            right: 6%;
            bottom: 12%;
            z-index: 10;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            min-width: 220px;
            animation: floatY 5s ease-in-out infinite;
        }

        @keyframes floatY {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Nav */
        .nav-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            padding: 1.25rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.4s, box-shadow 0.4s;
        }

        .nav-wrapper.scrolled {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            box-shadow: 0 1px 0 rgba(139, 92, 246, 0.1);
        }

        /* Description section */
        .desc-section {
            background: #f8fafc;
        }

        .feature-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 2.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(124, 58, 237, 0.08);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            box-shadow: 0 25px 50px rgba(124, 58, 237, 0.2);
            transform: translateY(-6px);
            border-color: rgba(124, 58, 237, 0.3);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:focus-visible {
            outline: 2px solid #7C3AED;
            outline-offset: 4px;
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #e0e7ff, #f3e8ff);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Process section */
        .process-section {
            background: #0f172a;
        }

        .step-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 2px solid rgba(124, 58, 237, 0.5);
            background: rgba(124, 58, 237, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #8B5CF6;
            transition: background 0.3s, border-color 0.3s;
        }

        .step-item:hover .step-circle {
            background: rgba(124, 58, 237, 0.25);
            border-color: #8B5CF6;
        }

        /* CTA section */
        .cta-section {
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 60%, #1e293b 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -120px;
            left: -120px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-btn-primary {
            background: linear-gradient(135deg, #7C3AED, #6D28D9);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 32px rgba(124, 58, 237, 0.35);
        }

        .cta-btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 48px rgba(124, 58, 237, 0.5);
        }

        .cta-btn-primary:active {
            transform: translateY(-2px);
        }

        .cta-btn-primary:focus {
            outline: 2px solid rgba(139, 92, 246, 0.5);
            outline-offset: 2px;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        /* Responsive hero */
        @media (max-width: 768px) {
            .hero-wrapper {
                grid-template-columns: 1fr;
                min-height: auto;
                padding-bottom: 3rem;
            }
            
            .hero-left {
                grid-column: 1;
                padding: 6rem 1.5rem 2rem;
            }
            
            .hero-left::before {
                background: none;
            }
            
            .hero-right {
                grid-column: 1;
                transform: none;
                margin: 2rem 1.5rem;
                height: 300px;
                border-radius: 16px;
            }
            
            .hero-float-card {
                display: none;
            }
            
            .nav-wrapper {
                padding: 1rem 1.5rem;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- NAV -->
    <nav class="nav-wrapper" id="mainNav">
        <div class="font-display font-bold text-xl text-white tracking-tight w-35">
            <img src="{{ asset('images/polman.png') }}" alt="Logo">
        </div>
        
        <!-- Desktop login button -->
        <a href="/login"
            class="hidden sm:inline-block text-sm font-medium text-white bg-violet-900 hover:text-white border border-slate-600 hover:bg-violet-400 hover:border-white px-4 py-2 rounded-lg transition-all duration-200">
            Login
        </a>
        
        <!-- Mobile hamburger menu -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <!-- Mobile menu -->
        <div class="mobile-menu" id="mobileMenu">
            <a href="#deskripsi">Fitur</a>
            <a href="#alur">Alur Kerja</a>
            <a href="/login" class="text-violet-400">Login</a>
        </div>
    </nav>


    <!-- SECTION HERO -->
    <section class="hero-wrapper">

        <!-- Background image panel (right) -->
        <div class="hero-right"></div>

        <!-- Text panel (left) -->
        <div class="hero-left">
            <div class="space-y-8">

                <!-- Eyebrow -->
                <div class="flex items-center gap-3">
                    <div class="accent-line"></div>
                    <span class="text-violet-600 text-xs font-semibold uppercase tracking-wider">Pemeriksa Panduan
                        Kurikulum</span>
                </div>

                <!-- Wordmark -->
                <div>
                    <h1 class="font-serif font-bold leading-tight text-white text-balance"
                        style="font-size: clamp(2.8rem,6vw,4.5rem); letter-spacing: -0.03em;">
                        Persiapan Akreditasi
                    </h1>
                    <h1 class="font-serif font-bold leading-tight text-violet-400 text-balance"
                        style="font-size: clamp(2.8rem,6vw,4.5rem); letter-spacing: -0.03em;">
                        IABEE
                    </h1>
                </div>

                <!-- Sub -->
                <p class="text-slate-300 text-base leading-relaxed-lg max-w-sm font-light">
                    Platform infrastruktur evaluasi untuk pemeriksa panduan kurikulum disesuaikan dengan standar
                    kriteria IABEE — dirancang untuk validator, administrator, dan pemangku kepentingan program studi
                    Polman Bandung.
                </p>

                <!-- CTA -->
                <div class="flex items-center gap-4 pt-2 flex-wrap">
                    <a href="/login" class="cta-btn-primary">
                        Masuk ke Dashboard
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <a href="#deskripsi" class="text-slate-400 text-sm hover:text-white transition-colors">Pelajari
                        lebih</a>
                </div>

                <!-- Stats row -->
                <div class="flex gap-4 pt-4 flex-wrap">
                    <div class="stat-badge rounded-xl px-4 py-3">
                        <div class="font-display font-bold text-white text-xl">9</div>
                        <div class="text-slate-400 text-xs mt-0.5">Kriteria IABEE</div>
                    </div>
                    <div class="stat-badge rounded-xl px-4 py-3">
                        <div class="font-display font-bold text-white text-xl">Real-time</div>
                        <div class="text-slate-400 text-xs mt-0.5">Agregasi Data</div>
                    </div>
                    <div class="stat-badge rounded-xl px-4 py-3">
                        <div class="font-display font-bold text-white text-xl">RBAC</div>
                        <div class="text-slate-400 text-xs mt-0.5">Kontrol Akses</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating card (overlays the right side) -->
        <!-- <div class="hero-float-card">
            <div class="text-xs text-slate-400 mb-2 font-medium uppercase tracking-wider">CPL Terkini</div>
            <div class="flex items-end gap-2">
                <span class="font-display font-bold text-white text-3xl">87<span class="text-violet-400">%</span></span>
                <span class="text-green-400 text-xs mb-1">↑ 4.2%</span>
            </div>
            <div class="mt-3 flex gap-1">
                <div class="h-1.5 rounded-full bg-blue-500" style="width:87%"></div>
                <div class="h-1.5 rounded-full bg-slate-700 flex-1"></div>
            </div>
            <div class="text-slate-500 text-xs mt-1.5">Target: 90%</div>
        </div> -->

        <!-- Scroll hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 opacity-40">
            <span class="text-white text-xs tracking-wider uppercase">Scroll</span>
            <div class="w-px h-8 bg-linear-to-b from-white to-transparent"></div>
        </div>
    </section>


    <!-- SECTION DESKRIPSI & FITUR -->
    <section id="deskripsi" class="desc-section py-28 px-6">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-16 reveal">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="accent-line"></div>
                    <span class="text-violet-600 text-xs font-semibold uppercase tracking-wider">Kemampuan Sistem</span>
                    <div class="accent-line"></div>
                </div>
                <h2 class="font-serif font-bold text-slate-900 text-4xl sm:text-5xl mb-5 text-balance"
                    style="letter-spacing: -0.02em;">
                    Infrastruktur yang <span class="text-violet-600">Andal</span>
                </h2>
                <p class="text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Dibangun secara untuk memenuhi kompleksitas teknis evaluasi akreditasi IABEE dengan presisi dan
                    integritas data penuh.
                </p>
            </div>

            <!-- Feature grid -->
            <div class="feature-grid">

                <!-- Large card: Left side, spans 2 rows -->
                <div class="feature-card feature-card-large reveal" style="transition-delay:0.1s">
                    <div class="feature-icon mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="#7C3AED" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 3v18h18" />
                            <path d="m19 9-5 5-4-4-3 3" />
                        </svg>
                    </div>
                    <h3 class="font-serif font-semibold text-slate-900 text-2xl mb-4">Integritas Metrik</h3>
                    <p class="text-slate-500 text-base leading-relaxed-lg">Pemrosesan data mentah menjadi metrik evaluasi terstruktur yang selaras penuh dengan taksonomi dan bobot kriteria IABEE. Setiap data point divalidasi dan dikualifikasi melalui pipeline integrity check yang ketat.</p>
                </div>

                <!-- Right column: 2 stacked cards -->
                <div class="feature-card reveal" style="transition-delay:0.2s">
                    <div class="feature-icon mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="#7C3AED" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect width="8" height="4" x="8" y="2" rx="1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <path d="M12 11h4" />
                            <path d="M12 16h4" />
                            <path d="M8 11h.01" />
                            <path d="M8 16h.01" />
                        </svg>
                    </div>
                    <h3 class="font-serif font-semibold text-slate-900 text-lg mb-3">Agregasi Laporan</h3>
                    <p class="text-slate-500 text-sm leading-relaxed-lg">Generasi rekapitulasi Capaian Pembelajaran Lulusan (CPL) secara real-time dengan visualisasi yang siap untuk dokumen asesmen resmi.</p>
                </div>

                <div class="feature-card reveal" style="transition-delay:0.3s">
                    <div class="feature-icon mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="#7C3AED" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <h3 class="font-serif font-semibold text-slate-900 text-lg mb-3">Keamanan Akses</h3>
                    <p class="text-slate-500 text-sm leading-relaxed-lg">Infrastruktur tertutup dengan Role-Based Access Control (RBAC) — memastikan setiap pengguna hanya mengakses data yang relevan dengan perannya.</p>
                </div>

            </div>
        </div>
    </section>


    <!-- SECTION ALUR KERJA -->
    <section id="alur" class="process-section py-28 px-6">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-20 reveal">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="accent-line"></div>
                    <span class="text-violet-400 text-xs font-semibold uppercase tracking-wider">Alur Kerja
                        Sistem</span>
                    <div class="accent-line"></div>
                </div>
                <h2 class="font-serif font-bold text-white text-4xl sm:text-5xl mb-5 text-balance"
                    style="letter-spacing: -0.02em;">
                    Dari Data ke <span class="text-violet-400">Keputusan</span>
                </h2>
                <p class="text-slate-400 max-w-lg mx-auto leading-relaxed">
                    Empat tahap terstruktur yang mengubah data mentah program studi menjadi laporan akreditasi yang
                    actionable.
                </p>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">

                <div class="step-item text-center reveal" style="transition-delay:0.05s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">01</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Input Data</h4>
                    <p class="text-slate-400 text-sm leading-relaxed-lg">Administrator menginput capaian mahasiswa, nilai
                        matakuliah, dan dokumen pendukung ke dalam sistem secara terpusat.</p>
                </div>

                <div class="step-item text-center reveal" style="transition-delay:0.15s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">02</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Pemetaan CPL</h4>
                    <p class="text-slate-400 text-sm leading-relaxed-lg">Sistem secara otomatis memetakan data ke Capaian
                        Pembelajaran Lulusan sesuai kurikulum yang telah dikonfigurasi.</p>
                </div>

                <div class="step-item text-center reveal" style="transition-delay:0.25s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">03</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Analisis Metrik</h4>
                    <p class="text-slate-400 text-sm leading-relaxed-lg">Kalkulasi metrik evaluasi terhadap 9 kriteria
                        IABEE dilakukan secara real-time dengan deteksi gap otomatis.</p>
                </div>

                <div class="step-item text-center reveal" style="transition-delay:0.35s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">04</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Ekspor Laporan</h4>
                    <p class="text-slate-400 text-sm leading-relaxed-lg">Laporan final digenerate dalam format
                        siap-asesmen yang sesuai dengan template dokumen akreditasi IABEE.</p>
                </div>

            </div>
        </div>
    </section>


    <!-- SECTION CTA -->
    <section class="cta-section py-28 px-6">
        <div class="max-w-4xl mx-auto text-center relative z-10">

            <div class="reveal">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="accent-line"></div>
                    <span class="text-violet-400 text-xs font-semibold uppercase tracking-wider">Akses Terbatas</span>
                    <div class="accent-line"></div>
                </div>
                <h2 class="font-serif font-bold text-white text-4xl sm:text-5xl mb-6 leading-tight text-balance"
                    style="letter-spacing: -0.02em;">
                    Siap untuk Memulai<br>Persiapan <span class="text-violet-400">Akreditasi</span>?
                </h2>
                <p class="text-slate-300 max-w-lg mx-auto mb-10 leading-relaxed">
                    Platform ini diperuntukkan secara bagi asesor, administrator, dan pemangku kepentingan yang telah
                    memiliki kredensial akses resmi.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/login" class="cta-btn-primary">
                        Masuk ke Dashboard Utama
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        Akses aman dengan enkripsi penuh
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- FOOTER -->
    <footer style="background:#060e1c; border-top: 1px solid rgba(255,255,255,0.05);">
        <div class="max-w-7xl mx-auto py-8 px-6 flex flex-col sm:flex-row justify-between items-center gap-3">
            <div class="font-display font-bold text-white text-lg">
                Sistem Persiapan <span class="text-violet-400">IABEE</span>
            </div>
            <p class="text-slate-500 text-sm">
                &copy; {{ date('Y') }} Infrastruktur Evaluasi. Dibangun untuk skala operasional.
            </p>
        </div>
    </footer>


    <script>
        // Nav scroll effect
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        });

        // Scroll reveal
        const revealEls = document.querySelectorAll('.reveal');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    io.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.12
        });
        revealEls.forEach(el => io.observe(el));

        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuToggle.addEventListener('click', () => {
            mobileMenuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });

        // Close menu when link clicked
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            });
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                mobileMenuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });
    </script>

</body>

</html>
