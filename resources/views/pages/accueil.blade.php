@extends('pages.layouts.template')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="hero-badge">
                        <span>Plateforme de réservation</span>
                    </div>
                    <h1 class="hero-title">
                        Gérez vos réservations de <span class="highlight-text">messes en toute simplicité</span>
                    </h1>
                    <p class="hero-subtitle text-center">
                        E-MESSE est une plateforme complète et intuitive qui vous permet de gérer vos
                        intentions de messe, vos réservations et vos offrandes en toute sécurité depuis n'importe où.
                    </p>
                    <div class="hero-buttons content-justify-center">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="fas fa-rocket"></i> Commencer
                        </a>
                        <a href="#how-it-works" class="btn btn-outline-primary">
                            <i class="fas fa-play-circle"></i> En savoir plus
                        </a>
                    </div>
                </div>
                {{-- <div class="col-lg-6">
                    <div class="hero-image-container">
                        <img src="{{ asset('assets/assets/images/hero-illustration.svg') }}" alt="E-MESSE Illustration"
                            class="hero-image" onerror="this.style.display='none'">
                        <!-- Fallback illustration -->
                        <div class="church-illustration">
                            <div class="church-building">
                                <div class="church-tower"></div>
                                <div class="church-body">
                                    <div class="church-windows">
                                        <div class="window"></div>
                                        <div class="window"></div>
                                    </div>
                                    <div class="church-door"></div>
                                </div>
                            </div>
                            <div class="sun"></div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- Statistics Bar -->
        <div class="stats-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="stat-item">
                            <h3 class="stat-number">{{ $statistics->parishes_count ?? '500+' }}</h3>
                            <p class="stat-label">Paroisses inscrites</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="stat-item">
                            <h3 class="stat-number">{{ $statistics->users_count ?? '50K+' }}</h3>
                            <p class="stat-label">Utilisateurs actifs</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="stat-item">
                            <h3 class="stat-number">{{ $statistics->availability ?? '99.9%' }}</h3>
                            <p class="stat-label">De disponibilité</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose E-MESSE Section -->
    <section id="features" class="features-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Pourquoi choisir E-MESSE ?</h2>
                    <p class="section-subtitle">Ce que fait toute la différence</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Disponibilité 24/7</h4>
                        <p>Gérez vos réservations à toute heure et en toute liberté</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>Assistance & communication</h4>
                        <p>Une équipe dédiée pour répondre à toutes vos questions rapidement</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Accessible Partout</h4>
                        <p>Accédez à votre compte depuis n'importe quel appareil connecté</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4>Gestion Unifiée</h4>
                        <p>Centralisez toutes vos données et gérez tout en un seul endroit</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Témoignages</h2>
                    <p class="section-subtitle">Ce que disent nos utilisateurs</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse($testimonials as $testimonial)
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $testimonial->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="testimonial-text">
                                "{{ $testimonial->message }}"
                            </p>
                            <div class="testimonial-author">
                                <strong>{{ $testimonial->name }}</strong>
                                <span>{{ $testimonial->location }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Aucun témoignage disponible pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section - Ready to Start -->
    <section class="cta-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="cta-title">Prêt à Démarrer ?</h2>
                    <p class="cta-subtitle">
                        Rejoignez des milliers de fidèles qui utilisent E-MESSE pour gérer leurs intentions de messe en
                        toute simplicité
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-cta">
                        <i class="fa fa-download"></i> Télécharger l'App
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Download Section -->
    <section class="download-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Télécharger E-MESSE</h2>


                    <div class="download-card">
                        <div class="download-icon">
                            <i class="fab fa-android"></i>
                        </div>
                        <p class="download-text">
                        <h2 class="section-subtitle mb-4 fw-bold" style="color: #000000;">Disponible sur Google Play</h2>
                        Téléchargez notre application mobile pour accéder à E-MESSE où que vous soyez
                        </p>
                        <a href="#" class="btn btn-google-play">
                            <i class="fas fa-download"></i> Télécharger maintenant
                        </a>
                        <p class="download-info">
                            L'application E-MESSE est disponible gratuitement
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Global Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Color Palette */
        :root {
            --primary-gold: #C5A572;
            --dark-gold: #A67D3D;
            --light-gold: #E8D5B5;
            --bg-cream: #FBF8F3;
            --text-dark: #2D2D2D;
            --text-gray: #6B7280;
            --white: #FFFFFF;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .section-padding {
            padding: 80px 0;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #FBF8F3 0%, #F5EFE6 100%);
            padding: 100px 0 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(197, 165, 114, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-badge {
            display: inline-block;
            background: #5ea7b5;
            padding: 8px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px var(--shadow);
            margin-bottom: 20px;
            animation: fadeInDown 0.8s ease;
        }

        .hero-badge span {
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 25px;
            color: var(--text-dark);
            animation: fadeInUp 0.8s ease;
        }

        .highlight-text {
            color: var(--primary-gold);
            position: relative;
            display: inline-block;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: var(--text-gray);
            margin-bottom: 35px;
            line-height: 1.8;
            /* max-width: 550px; */
            animation: fadeInUp 1s ease;
        }


        .btn {
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary-gold);
            box-shadow: 0 6px 20px rgba(197, 165, 114, 0.3);
        }

        .btn-primary:hover {
            background: #ffffff;
            transform: translateY(-3px);
            border: 2px solid var(--primary-gold);
            box-shadow: 0 10px 30px rgba(197, 165, 114, 0.4);
            color: var(--primary-gold);
        }

        .btn-outline-primary {
            background: #d9d9d9;
            color: #000;
            border: 2px solid #f2ece1;
        }

        .btn-outline-primary:hover {
            background: #a3a3a3;
            color: #000;
            border: 2px solid #f2ece1;
            transform: translateY(-3px);
        }

        /* Hero Image */
        .hero-image-container {
            background-color: #f2ece1;
            position: relative;
            animation: fadeInRight 1s ease;
        }

        .container {
            background-color: fafcfc;
            position: relative;
            animation: fadeInRight 1s ease;
        }

        .hero-image {
            width: 100%;
            height: auto;
            max-width: 500px;
        }

        /* Church Illustration */
        .church-illustration {
            position: relative;
            width: 100%;
            max-width: 400px;
            height: 350px;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            margin: 0 auto;
        }

        .church-building {
            position: relative;
            z-index: 2;
        }

        .church-tower {
            width: 70px;
            height: 140px;
            background: var(--white);
            margin: 0 auto;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            position: relative;
            box-shadow: 0 10px 30px var(--shadow);
        }

        .church-tower::after {
            content: '';
            position: absolute;
            top: -25px;
            left: -12px;
            width: 94px;
            height: 25px;
            background: var(--primary-gold);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .church-tower::before {
            content: '✝';
            position: absolute;
            top: -55px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 30px;
            color: var(--primary-gold);
        }

        .church-body {
            width: 220px;
            height: 170px;
            background: var(--white);
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            margin-top: -10px;
            position: relative;
            box-shadow: 0 10px 30px var(--shadow);
        }

        .church-door {
            width: 50px;
            height: 85px;
            background: var(--primary-gold);
            position: absolute;
            bottom: 0;
            left: 85px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .church-windows {
            display: flex;
            justify-content: space-around;
            padding: 35px 30px 0;
        }

        .window {
            width: 40px;
            height: 40px;
            background: var(--primary-gold);
            opacity: 0.5;
            border-radius: 50%;
        }

        .sun {
            position: absolute;
            top: 60px;
            right: 60px;
            width: 80px;
            height: 80px;
            background: var(--primary-gold);
            border-radius: 50%;
            box-shadow: 0 0 50px rgba(197, 165, 114, 0.5);
            animation: pulse 3s ease-in-out infinite;
        }

        /* Stats Bar */
        .stats-bar {
            background: var(--white);
            margin-top: 60px;
            padding: 40px 0;
            border-top: 1px solid #E5E7EB;
            box-shadow: 0 -4px 20px var(--shadow);
        }

        .stat-item {
            padding: 20px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-gold);
            margin-bottom: 8px;
        }

        .stat-label {
            color: var(--text-gray);
            font-size: 1rem;
            margin: 0;
        }

        /* Features Section */
        .features-section {
            background: var(--white);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .section-subtitle {
            font-size: 1.15rem;
            color: var(--text-gray);
        }

        .feature-card {
            background: var(--white);
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.4s ease;
            border: 2px solid #F3F4F6;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px var(--shadow);
            border-color: var(--light-gold);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--light-gold) 0%, var(--primary-gold) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-icon i {
            font-size: 2rem;
            color: var(--white);
        }

        .feature-card h4 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .feature-card p {
            color: var(--text-gray);
            line-height: 1.7;
            margin: 0;
        }

        /* Testimonials Section */
        .testimonials-section {
            background: #ffffff;
        }

        .testimonial-card {
            background: var(--white);
            padding: 35px;
            border-radius: 20px;
            border: 3px solid #F3F4F6;
            /* box-shadow: 0 1px 10px var(--shadow); */
            height: 100%;
            transition: all 0.4s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(197, 165, 114, 0.2);
        }

        .stars {
            margin-bottom: 20px;
        }

        .stars i {
            color: #FFA500;
            font-size: 1.1rem;
            margin-right: 4px;
        }

        .testimonial-text {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--text-gray);
            margin-bottom: 25px;
            font-style: italic;
        }

        .testimonial-author strong {
            display: block;
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .testimonial-author span {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* CTA Section */
        .cta-section {
            background: #faf4e6;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='30' cy='30' r='1.5' fill='%23ffffff' fill-opacity='0.1'/%3E%3C/svg%3E");
            background-size: 60px 60px;
        }

        .cta-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: #000000;
            margin-bottom: 20px;
            position: relative;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            color: #7f8281;
            margin-bottom: 35px;
            position: relative;
        }

        .btn-cta {
            background: #d6bc85 !important;
            color: #ffffff;
            font-size: 1.1rem;
            padding: 16px 40px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .btn-cta:hover {
            color: #d6bc85;
            background: #ffffffff !important;
            border: 2px solid #d6bc85;

        }

        /* Download Section */
        .download-section {
            background: var(--white);
        }

        .download-card {
            background: #f5f9fa;
            padding: 60px 40px;
            border-radius: 25px;
            box-shadow: 0 8px 20px var(--shadow);
            border: 3px solid var(--light-gold);
        }

        .download-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--primary-gold) 0%, var(--dark-gold) 100%);
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }

        .download-icon i {
            font-size: 3rem;
            color: var(--white);
        }

        .download-text {
            font-size: 1.1rem;
            color: var(--text-gray);
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-google-play {
            background: #c4a35a;
            color: #ffffff;
            font-size: 1.1rem;
            padding: 16px 40px;
            box-shadow: 0 6px 20px #c4a35a;
        }

        .btn-google-play:hover {
            background: #ffffffff;
            transform: translateY(-3px);
            box-shadow: 0 4px 10px #c4a35a;
            border: 1px solid #c4a35a;
            color: #c4a35a;
        }

        .download-info {
            margin-top: 20px;
            color: var(--text-gray);
            font-size: 0.95rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta-title {
                font-size: 2.2rem;
            }

            .church-illustration {
                margin-top: 40px;
                max-width: 300px;
                height: 280px;
            }
        }

        @media (max-width: 768px) {
            .section-padding {
                padding: 60px 0;
            }

            .hero-section {
                padding: 60px 0 0;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .stat-number {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .cta-title {
                font-size: 1.8rem;
            }

            .download-card {
                padding: 40px 25px;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .feature-card,
            .testimonial-card {
                padding: 30px 20px;
            }

            .stats-bar {
                margin-top: 40px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Intersection Observer for scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe feature cards and testimonials
            const animateElements = document.querySelectorAll('.feature-card, .testimonial-card');
            animateElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease';
                observer.observe(el);
            });

            // Counter animation for stats
            const animateCounter = (element) => {
                const target = element.textContent;
                const isPercentage = target.includes('%');
                const number = parseFloat(target.replace(/[^0-9.]/g, ''));
                const duration = 2000;
                const steps = 60;
                const increment = number / steps;
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= number) {
                        current = number;
                        clearInterval(timer);
                    }

                    if (isPercentage) {
                        element.textContent = current.toFixed(1) + '%';
                    } else if (target.includes('K')) {
                        element.textContent = Math.floor(current) + 'K+';
                    } else {
                        element.textContent = Math.floor(current) + '+';
                    }
                }, duration / steps);
            };

            // Observe stats
            const statsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statNumber = entry.target.querySelector('.stat-number');
                        if (statNumber && !statNumber.classList.contains('animated')) {
                            statNumber.classList.add('animated');
                            animateCounter(statNumber);
                        }
                        statsObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            document.querySelectorAll('.stat-item').forEach(stat => {
                statsObserver.observe(stat);
            });
        });
    </script>
@endsection
