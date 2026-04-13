<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Evaluasi IABEE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        html,
        body {
            font-family: 'DM Sans', sans-serif;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* Edge/IE */
            background-color: #0a1628;
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

        /* Hero split gradient */
        .hero-wrapper {
            position: relative;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .hero-left {
            position: relative;
            z-index: 2;
            flex: 0 0 52%;
            background-color: #0a1628;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 7rem 5rem 7rem 6rem;
        }

        .hero-left::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;

            /* Pattern grid */
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 40px 40px;

            -webkit-mask-image: radial-gradient(circle at top left, black 0%, transparent 60%);
            mask-image: radial-gradient(circle at top left, black 0%, transparent 80%);
        }

        /* Diagonal bleed edge of left panel */
        .hero-left::after {
            content: '';
            position: absolute;
            top: 0;
            right: -80px;
            bottom: 0;
            width: 160px;

            background-color: #0a1628;

            clip-path: polygon(0 0, 40% 0, 100% 100%, 0 100%);
            z-index: 3;
        }

        .hero-glow-bottom {
            position: absolute;
            width: 500px;
            height: 500px;
            bottom: -150px;
            right: -150px;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.25), transparent 70%);
            filter: blur(120px);
        }

        /* Right panel */
        .hero-right {
            position: absolute;
            inset: 0;
            z-index: 1;
            background-image: url('https://images.unsplash.com/photo-1606761568499-6d2451b23c66?w=1400&q=80');
            background-size: cover;
            background-position: center right;
        }

        /* Gradient mask */
        .hero-right::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    #0a1628 0%,
                    #0a1628 42%,
                    rgba(10, 22, 40, 0.85) 58%,
                    rgba(10, 22, 40, 0.3) 78%,
                    rgba(10, 22, 40, 0.05) 100%);
        }

        /* Accent line */
        .accent-line {
            width: 48px;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            border-radius: 2px;
        }

        /* Stat badge */
        .stat-badge {
            border: 1px solid rgba(59, 130, 246, 0.3);
            background: rgba(59, 130, 246, 0.07);
            backdrop-filter: blur(8px);
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
            background: rgba(10, 22, 40, 0.95);
            backdrop-filter: blur(12px);
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        /* Description section */
        .desc-section {
            background: #f8fafc;
        }

        .feature-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 2.5rem;
            transition: box-shadow 0.3s, transform 0.3s, border-color 0.3s;
            position: relative;
            overflow: hidden;
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
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.1);
            transform: translateY(-4px);
            border-color: rgba(59, 130, 246, 0.2);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Process section */
        .process-section {
            background: #0a1628;
        }

        .step-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 2px solid rgba(59, 130, 246, 0.5);
            background: rgba(59, 130, 246, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #60a5fa;
            transition: background 0.3s, border-color 0.3s;
        }

        .step-item:hover .step-circle {
            background: rgba(59, 130, 246, 0.25);
            border-color: #60a5fa;
        }

        /* CTA section */
        .cta-section {
            background: linear-gradient(135deg, #1e3a5f 0%, #0a1628 60%, #0f2444 100%);
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
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, transparent 70%);
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
            background: radial-gradient(circle, rgba(96, 165, 250, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-btn-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 24px rgba(59, 130, 246, 0.35);
        }

        .cta-btn-primary:hover {
            transform: translateY(-3px);
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
            .hero-left {
                flex: 0 0 100%;
                padding: 8rem 2rem 4rem;
                background: linear-gradient(180deg, #0a1628 60%, rgba(10, 22, 40, 0.97) 100%);
            }

            .hero-left::after {
                display: none;
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
        <a href="/login" class="text-sm font-medium text-white bg-blue-900 hover:text-white border border-slate-600 hover:bg-blue-400 hover:border-white px-4 py-2 rounded-lg transition-all duration-200">
            Login
        </a>
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
                    <span class="text-blue-400 text-xs font-semibold uppercase tracking-widest">Platform Akreditasi</span>
                </div>

                <!-- Wordmark -->
                <div>
                    <h1 class="font-display font-black leading-[1.05] text-white" style="font-size: clamp(2.6rem,5vw,4.2rem);">
                        Persiapan Akreditasi
                    </h1>
                    <h1 class="font-display font-black leading-[1.05] text-blue-400" style="font-size: clamp(2.6rem,5vw,4.2rem);">
                        IABEE
                    </h1>
                </div>

                <!-- Sub -->
                <p class="text-slate-300 text-base leading-relaxed max-w-sm font-light">
                    Platform infrastruktur evaluasi untuk pemenuhan standar kriteria IABEE — dirancang untuk asesor, administrator, dan pemangku kepentingan program studi Polman Bandung.
                </p>

                <!-- CTA -->
                <div class="flex items-center gap-4 pt-2 flex-wrap">
                    <a href="/login" class="cta-btn-primary">
                        Masuk ke Dashboard
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <a href="#deskripsi" class="text-slate-400 text-sm hover:text-white transition-colors">Pelajari lebih</a>
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
                <span class="font-display font-bold text-white text-3xl">87<span class="text-blue-400">%</span></span>
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
            <span class="text-white text-xs tracking-widest uppercase">Scroll</span>
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
                    <span class="text-blue-600 text-xs font-semibold uppercase tracking-widest">Kemampuan Sistem</span>
                    <div class="accent-line"></div>
                </div>
                <h2 class="font-display font-black text-slate-900 text-4xl sm:text-5xl mb-5">
                    Infrastruktur yang <span class="text-blue-600">Andal</span>
                </h2>
                <p class="text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Dibangun secara untuk memenuhi kompleksitas teknis evaluasi akreditasi IABEE dengan presisi dan integritas data penuh.
                </p>
            </div>

            <!-- Feature grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="feature-card reveal" style="transition-delay:0.1s">
                    <div class="feature-icon mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18" />
                            <path d="m19 9-5 5-4-4-3 3" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Integritas Metrik</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Pemrosesan data mentah menjadi metrik evaluasi terstruktur yang selaras penuh dengan taksonomi dan bobot kriteria IABEE.</p>
                </div>

                <div class="feature-card reveal" style="transition-delay:0.2s">
                    <div class="feature-icon mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="8" height="4" x="8" y="2" rx="1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <path d="M12 11h4" />
                            <path d="M12 16h4" />
                            <path d="M8 11h.01" />
                            <path d="M8 16h.01" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Agregasi Laporan</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Generasi rekapitulasi Capaian Pembelajaran Lulusan (CPL) secara real-time dengan visualisasi yang siap untuk dokumen asesmen resmi.</p>
                </div>

                <div class="feature-card reveal" style="transition-delay:0.3s">
                    <div class="feature-icon mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Keamanan Akses</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Infrastruktur tertutup dengan Role-Based Access Control (RBAC) — memastikan setiap pengguna hanya mengakses data yang relevan dengan perannya.</p>
                </div>

            </div>
        </div>
    </section>


    <!-- SECTION ALUR KERJA -->
    <section class="process-section py-28 px-6">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-20 reveal">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="accent-line"></div>
                    <span class="text-blue-400 text-xs font-semibold uppercase tracking-widest">Alur Kerja Sistem</span>
                    <div class="accent-line"></div>
                </div>
                <h2 class="font-display font-black text-white text-4xl sm:text-5xl mb-5">
                    Dari Data ke <span class="text-blue-400">Keputusan</span>
                </h2>
                <p class="text-slate-400 max-w-lg mx-auto leading-relaxed">
                    Empat tahap terstruktur yang mengubah data mentah program studi menjadi laporan akreditasi yang actionable.
                </p>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <div class="step-item text-center reveal" style="transition-delay:0.05s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">01</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Input Data</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Administrator menginput capaian mahasiswa, nilai matakuliah, dan dokumen pendukung ke dalam sistem secara terpusat.</p>
                </div>

                <div class="step-item text-center reveal" style="transition-delay:0.15s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">02</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Pemetaan CPL</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Sistem secara otomatis memetakan data ke Capaian Pembelajaran Lulusan sesuai kurikulum yang telah dikonfigurasi.</p>
                </div>

                <div class="step-item text-center reveal" style="transition-delay:0.25s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">03</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Analisis Metrik</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Kalkulasi metrik evaluasi terhadap 9 kriteria IABEE dilakukan secara real-time dengan deteksi gap otomatis.</p>
                </div>

                <div class="step-item text-center reveal" style="transition-delay:0.35s">
                    <div class="flex justify-center mb-5">
                        <div class="step-circle">04</div>
                    </div>
                    <h4 class="font-bold text-white mb-2">Ekspor Laporan</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Laporan final digenerate dalam format siap-asesmen yang sesuai dengan template dokumen akreditasi IABEE.</p>
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
                    <span class="text-blue-400 text-xs font-semibold uppercase tracking-widest">Akses Terbatas</span>
                    <div class="accent-line"></div>
                </div>
                <h2 class="font-display font-black text-white text-4xl sm:text-5xl mb-6 leading-tight">
                    Siap untuk Memulai<br>Persiapan <span class="text-blue-400">Akreditasi</span>?
                </h2>
                <p class="text-slate-300 max-w-lg mx-auto mb-10 leading-relaxed">
                    Platform ini diperuntukkan secara bagi asesor, administrator, dan pemangku kepentingan yang telah memiliki kredensial akses resmi.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/login" class="cta-btn-primary">
                        Masuk ke Dashboard Utama
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                Sistem Persiapan <span class="text-blue-400">IABEE</span>
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
    </script>

</body>

</html>