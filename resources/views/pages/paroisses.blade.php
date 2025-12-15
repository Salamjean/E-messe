@extends('pages.layouts.template')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section"
        style="background-image: url('{{ asset('assets/image-backgroud-site/prete.png') }}'); background-size: cover; background-position: center; position: relative;">
        <div class="overlay"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(99, 112, 103, 0.1);">
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="hero-title" style="color: #fff;">Solutions pour vos <span
                            class="highlight-text">Paroisses</span></h1>
                    <p class="hero-subtitle" style="color: #fff;">Adaptée spécifiquement aux besoins des églises et
                        communautés religieuses</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Bénéfices pour votre Paroisse</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fa-solid fa-user-group">‌</i>
                        </div>
                        <h4>Gestion Collective</h4>
                        <p>Coordonner facilement avec votre équipe pastorale.</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check-circle"></i> Accès multi-utilisateurs</li>
                            <li><i class="fas fa-check-circle"></i> Rôles et permissions</li>
                            <li><i class="fas fa-check-circle"></i> Historique des actions</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fa-solid fa-table-cells-large">‌</i>
                        </div>
                        <h4>Statistiques Détaillées</h4>
                        <p>Analysez vos activités et prenez des décisions éclairées</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check-circle"></i> Rapports personnalisés</li>
                            <li><i class="fas fa-check-circle"></i> Graphiques interactifs</li>
                            <li><i class="fas fa-check-circle"></i> Exportation en PDF</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fa-solid fa-bolt">‌</i>
                        </div>
                        <h4>Notifications Intelligentes</h4>
                        <p>Gardez votre communauté informée en temps réel</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check-circle"></i> SMS et Email</li>
                            <li><i class="fas fa-check-circle"></i> Notifications push</li>
                            <li><i class="fas fa-check-circle"></i> Programmation flexible</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fa-solid fa-lock">‌</i>
                        </div>
                        <h4>Sécurité Ecclésiastique</h4>
                        <p>Vos données sensibles sont protégées selon les standards du Vatican</p>
                        <ul class="benefit-list">
                            <li><i class="fas fa-check-circle"></i> Chiffrement RGPD</li>
                            <li><i class="fas fa-check-circle"></i> Sauvegarde automatique</li>
                            <li><i class="fas fa-check-circle"></i> Restauration garantie</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="success-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Succès de nos Paroisses</h2>
                </div>
            </div>
            <div class="row g-4">
                @forelse($successStories as $story)
                    <div class="col-md-4">
                        <div class="success-card">
                            <div class="success-icon">
                                <i class="fas fa-church"></i>
                            </div>
                            <h4>{{ $story->name }}</h4>
                            <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $story->location }}</p>
                            @if ($story->participation_increase)
                                <p class="success-stat"><strong>{{ $story->participation_increase }}</strong> de
                                    participation aux messes</p>
                            @endif
                            <p class="description">{{ $story->description }}</p>
                            <div class="stats-details">
                                <div class="stat">
                                    <strong>{{ $story->active_users }}+</strong>
                                    <span>Utilisateurs actifs</span>
                                </div>
                                <div class="stat">
                                    <strong>{{ $story->masses_reserved }}</strong>
                                    <span>Messes réservées</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Aucune success story disponible pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Specialized Features Section -->
    <section class="features-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Fonctionnalités Spécialisées</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-item-horizontal">
                        <div class="feature-icon-horizontal">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Gestion Multi-Prêtres</h4>
                            <p>Permettez à tous les prêtres de la paroisse de gérer leurs messes individuellement tout en
                                centralisant les données.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-item-horizontal">
                        <div class="feature-icon-horizontal">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Supports Pastoraux</h4>
                            <p>Partagez des ressources spirituelles avec vos fidèles directement via la plateforme.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="feature-item-horizontal">
                        <div class="feature-icon-horizontal">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Liturgie Personnalisée</h4>
                            <p>Adaptez les horaires de messe, les types de célébrations et la capacité d'accueil selon les
                                besoins de votre communauté.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Questions Fréquentes des Paroisses</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        @forelse($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $index }}">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center">
                                <p class="text-muted">Aucune FAQ disponible pour le moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section section-padding">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-lg-12">
                    <h2 class="mb-3">Modernisez votre Paroisse avec E-MESSE</h2>
                    <p class="mb-4">Rejoignez nos centaines de paroisses et améliorez votre organisation dès aujourd'hui
                    </p>
                    <a href="#" class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#downloadAppModal"><i
                            class="fas fa-download"></i> Télécharger App</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Global Styles */
        :root {
            --primary-color: #C9974C;
            --primary-dark: #B38540;
            --text-dark: #2C2C2C;
            --text-light: #6B6B6B;
            --bg-light: #F8F6F3;
            --white: #FFFFFF;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
            background-color: var(--white);
        }

        /* Hero Section */
        .hero-section {
            background: #f7f4eb;
            padding: 100px 0 80px;
            position: relative;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
        }

        .highlight-text {
            color: var(--primary-color);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Section Padding */
        .section-padding {
            padding: 80px 0;
        }

        /* Section Titles */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            color: var(--text-dark);
        }

        /* Benefits Section */
        .benefits-section {
            background: var(--white);
        }

        .benefit-card {
            background: var(--white);
            border: 1px solid #E8E8E8;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(201, 151, 76, 0.15);
        }

        .benefit-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .benefit-card h4 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: var(--text-dark);
        }

        .benefit-card>p {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .benefit-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .benefit-list li {
            padding: 8px 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .benefit-list i {
            color: var(--primary-color);
            margin-right: 10px;
            font-size: 0.8rem;
        }

        /* Success Section */
        .success-section {
            background: var(--bg-light);
        }

        .success-card {
            background: var(--white);
            border-radius: 12px;
            padding: 35px 25px;
            height: 100%;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .success-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .success-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.2rem;
        }

        .success-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: var(--text-dark);
        }

        .success-card .location {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .success-card .location i {
            color: var(--primary-color);
            margin-right: 5px;
        }

        .success-stat {
            font-size: 1.1rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .success-card .description {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .stats-details {
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #E8E8E8;
            padding-top: 20px;
            margin-top: 20px;
        }

        .stats-details .stat {
            text-align: center;
        }

        .stats-details .stat strong {
            display: block;
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stats-details .stat span {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        /* Features Section */
        .features-section {
            background: var(--white);
        }

        .feature-item-horizontal {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 25px;
            background: var(--bg-light);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .feature-item-horizontal:hover {
            transform: translateX(10px);
            background: var(--white);
            box-shadow: var(--shadow);
        }

        .feature-icon-horizontal {
            font-size: 2.5rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .feature-content h4 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: var(--text-dark);
        }

        .feature-content p {
            color: var(--text-light);
            margin: 0;
            line-height: 1.6;
        }

        /* FAQ Section */
        .faq-section {
            background: var(--bg-light);
        }

        .accordion-item {
            border: 1px solid #E8E8E8;
            margin-bottom: 15px;
            border-radius: 8px !important;
            overflow: hidden;
        }

        .accordion-button {
            background: var(--white);
            color: var(--text-dark);
            font-weight: 600;
            padding: 20px 25px;
            font-size: 1.05rem;
        }

        .accordion-button:not(.collapsed) {
            background: var(--primary-color);
            color: var(--white);
        }

        .accordion-button:not(.collapsed) i {
            transform: rotate(45deg);
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }

        .accordion-button i {
            transition: transform 0.3s ease;
        }

        .accordion-body {
            padding: 25px;
            color: var(--text-light);
            line-height: 1.8;
        }

        /* CTA Section */
        .cta-section {
            background: #ffffff;
            color: var(--white);
        }

        .cta-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .cta-section p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        .btn-light {
            background: var(--white);
            color: var(--primary-color);
            border: none;
            padding: 15px 40px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background: var(--text-dark);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-cta {
            background: #D4A574;
            color: #ffffffff;
            padding: 16px 48px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
            box-shadow: 0 8px 24px rgba(212, 165, 116, 0.3);
            position: relative;
            z-index: 1;
        }

        .btn-cta:hover {
            background: #ffffffff;
            color: #D4A574;
            border: 1px solid #D4A574;
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(212, 165, 116, 0.4);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .hero-section,
            .section-padding {
                padding: 60px 0;
            }

            .feature-item-horizontal {
                flex-direction: column;
                text-align: center;
            }

            .stats-details {
                flex-direction: column;
                gap: 15px;
            }

            .btn-cta {
                padding: 14px 36px;
                font-size: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation for elements when they come into view
            const animatedElements = document.querySelectorAll(
                '.benefit-card, .success-card, .feature-item-horizontal');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });

            animatedElements.forEach(element => {
                element.style.opacity = 0;
                element.style.transform = 'translateY(30px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(element);
            });

            // Accordion icon rotation
            const accordionButtons = document.querySelectorAll('.accordion-button');
            accordionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.style.transform = this.classList.contains('collapsed') ?
                            'rotate(0deg)' : 'rotate(45deg)';
                    }
                });
            });
        });
    </script>
@endsection
