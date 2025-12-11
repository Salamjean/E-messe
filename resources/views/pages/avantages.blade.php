@extends('pages.layouts.template')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section"
        style="background-image: url('{{ asset('assets/image-backgroud-site/confirmation.png') }}'); background-size: cover; background-position: center; position: relative;">
        <div class="overlay"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(99, 112, 103, 0.1);">
        </div>
        <div class="container" style="position: relative; z-index: 1;">
            <div class="row text-center">
                <div class="col-lg-10 mx-auto">
                    <h1 class="hero-title" style="color: #fff;">Nos <span class="text-golden">Avantages</span></h1>
                    <p class="hero-subtitle" style="color: #fff;">Découvrez pourquoi E-Messe est la solution idéale pour
                        votre paroisse</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Advantages Cards Section -->
    <section class="advantages-section section-padding">
        <div class="container">
            <div class="row g-4">
                <!-- Gestion Efficace -->
                <div class="col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3>Gestion Efficace</h3>
                        <p class="text-muted mb-3">Optimisez la gestion de votre paroisse avec des outils modernes et
                            intuitifs</p>
                        <ul class="advantage-list">
                            <li><i class="fas fa-check-circle"></i> Automatisation complète du traitement des demandes</li>
                            <li><i class="fas fa-check-circle"></i> Réduction du temps de traitement administratif de 70%
                            </li>
                            <li><i class="fas fa-check-circle"></i> Suivi en temps réel de toutes les célébrations</li>
                            <li><i class="fas fa-check-circle"></i> Intégration facile dans vos systèmes existants</li>
                        </ul>
                    </div>
                </div>

                <!-- Gestion Efficace (bis) -->
                <div class="col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3>Gestion Efficace</h3>
                        <p class="text-muted mb-3">Améliorez la gestion de vos ressources financières et matérielles</p>
                        <ul class="advantage-list">
                            <li><i class="fas fa-check-circle"></i> Gestion transparente des offrandes et dons</li>
                            <li><i class="fas fa-check-circle"></i> Rapports financiers automatisés</li>
                            <li><i class="fas fa-check-circle"></i> Optimisation de la planification des célébrations</li>
                            <li><i class="fas fa-check-circle"></i> Réduction des coûts opérationnels</li>
                        </ul>
                    </div>
                </div>

                <!-- Sécurité Garantie -->
                <div class="col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Sécurité Garantie</h3>
                        <p class="text-muted mb-3">Protégez les données de votre paroisse avec le plus haut niveau de
                            sécurité</p>
                        <ul class="advantage-list">
                            <li><i class="fas fa-check-circle"></i> Cryptage de toutes les transactions financières</li>
                            <li><i class="fas fa-check-circle"></i> Conformité RGPD et protection des données</li>
                            <li><i class="fas fa-check-circle"></i> Sauvegarde automatique et sécurisée</li>
                            <li><i class="fas fa-check-circle"></i> Accès sécurisé avec authentification renforcée</li>
                        </ul>
                    </div>
                </div>

                <!-- Interface Intuitive -->
                <div class="col-md-6">
                    <div class="advantage-card">
                        <div class="advantage-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Interface Intuitive</h3>
                        <p class="text-muted mb-3">Une expérience utilisateur simple et accessible pour tous</p>
                        <ul class="advantage-list">
                            <li><i class="fas fa-check-circle"></i> Navigation simple et intuitive</li>
                            <li><i class="fas fa-check-circle"></i> Formation rapide de votre équipe</li>
                            <li><i class="fas fa-check-circle"></i> Support client disponible 7j/7</li>
                            <li><i class="fas fa-check-circle"></i> Interface adaptée aux mobiles et tablettes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Mesurable Section -->
    <section class="impact-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Impact Mesurable</h2>
                </div>
            </div>
            <div class="row g-4">
                @forelse($impacts as $impact)
                    <div class="col-md-3 col-6">
                        <div class="impact-card">
                            <h3 class="impact-number">{{ $impact->value }}</h3>
                            <p class="impact-label">{{ $impact->label }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Aucun impact disponible pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Comparison Table Section -->
    <section class="comparison-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Avant vs Après E-MESSE</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="comparison-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fonctionnalité</th>
                                    <th class="text-center">Avant</th>
                                    <th class="text-center">Avec E-MESSE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Enregistrement</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Prise de RDV Instantané</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Notifications en temps réel</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Paiement en Ligne</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Rapports et certificats</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Gestion multi-paroisses</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Support client</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>SÉCURITÉ RENFORCÉE</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i></td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i></td>
                                </tr>
                            </tbody>
                        </table>
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
                    <h2 class="section-title">Ce que disent nos clients</h2>
                </div>
            </div>
            <div class="row g-4">
                @forelse($testimonials as $testimonial)
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="stars mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $testimonial->rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="testimonial-text">"{{ $testimonial->message }}"</p>
                            <div class="testimonial-author">
                                <h5>{{ $testimonial->name }}</h5>
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

    <!-- CTA Section -->
    <section class="cta-section section-padding">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="cta-title mb-4">Prêt à découvrir ces avantages ?</h2>
                    <p class="cta-text mb-4">Rejoignez les centaines de paroisses qui ont déjà fait le choix de la
                        modernité</p>
                    <a href="#" class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#downloadAppModal"><i
                            class="fas fa-download"></i> Télécharger App</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Color Variables */
        :root {
            --primary-color: #c9a85c;
            --secondary-color: #8b7355;
            --dark-color: #2d2a26;
            --light-bg: #f9f7f4;
            --white: #ffffff;
            --text-dark: #333333;
            --text-muted: #6c757d;
        }

        /* Global Styles */
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            background-color: var(--white);
        }

        .section-padding {
            padding: 80px 0;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #f5f0e8 0%, #e8dfc8 100%);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9a85c' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.4;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 1rem;
            position: relative;
        }

        .text-golden {
            color: var(--primary-color);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Section Titles */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        /* Advantages Section */
        .advantages-section {
            background: var(--white);
        }

        .advantage-card {
            background: var(--white);
            border: 1px solid #e8dfc8;
            border-radius: 16px;
            padding: 40px 30px;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(201, 168, 92, 0.08);
        }

        .advantage-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(201, 168, 92, 0.15);
            border-color: var(--primary-color);
        }

        .advantage-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #f5f0e8 0%, #e8dfc8 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .advantage-icon i {
            font-size: 32px;
            color: var(--primary-color);
        }

        .advantage-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .advantage-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .advantage-list li {
            padding: 10px 0;
            color: var(--text-dark);
            display: flex;
            align-items: flex-start;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .advantage-list li i {
            color: var(--primary-color);
            margin-right: 12px;
            margin-top: 4px;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Impact Section */
        .impact-section {
            background: var(--light-bg);
        }

        .impact-card {
            background: var(--white);
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e8dfc8;
        }

        .impact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(201, 168, 92, 0.12);
        }

        .impact-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 10px;
            line-height: 1;
        }

        .impact-label {
            font-size: 1rem;
            color: var(--text-muted);
            margin: 0;
            font-weight: 500;
        }

        /* Comparison Section */
        .comparison-section {
            background: var(--white);
        }

        .comparison-table {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e8dfc8;
            box-shadow: 0 4px 20px rgba(201, 168, 92, 0.08);
        }

        .comparison-table table {
            margin-bottom: 0;
        }

        .comparison-table thead {
            background: linear-gradient(135deg, #f5f0e8 0%, #e8dfc8 100%);
        }

        .comparison-table thead th {
            color: var(--dark-color);
            font-weight: 700;
            padding: 20px;
            border: none;
            font-size: 1.1rem;
        }

        .comparison-table tbody td {
            padding: 18px 20px;
            border-bottom: 1px solid #f0f0f0;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .comparison-table tbody tr:last-child td {
            border-bottom: none;
        }

        .comparison-table tbody tr:hover {
            background-color: #fafaf8;
        }

        .comparison-table i {
            font-size: 20px;
        }

        /* Testimonials Section */
        .testimonials-section {
            background: var(--light-bg);
        }

        .testimonial-card {
            background: var(--white);
            border-radius: 16px;
            padding: 35px 30px;
            height: 100%;
            border: 1px solid #e8dfc8;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(201, 168, 92, 0.08);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(201, 168, 92, 0.15);
        }

        .stars i {
            color: var(--primary-color);
            font-size: 16px;
        }

        .testimonial-text {
            color: var(--text-dark);
            font-style: italic;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .testimonial-author h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .testimonial-author span {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* CTA Section */
        .cta-section {
            background: #ffffff;
            color: #000000;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #000000;
        }

        .cta-text {
            font-size: 1.2rem;
            color: #000000;
        }

        .btn-cta {
            background-color: #D4A574;
            color: #fff;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border: none;
        }

        .btn-cta:hover {
            background-color: #fff;
            transform: translateY(-2px);
            color: #D4A574;
            border: 1px solid #D4A574;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .cta-title {
                font-size: 1.8rem;
            }

            .section-padding {
                padding: 60px 0;
            }

            .advantage-card,
            .impact-card,
            .testimonial-card {
                margin-bottom: 20px;
            }

            .impact-number {
                font-size: 2.5rem;
            }
        }

        /* Animation */
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

        .advantage-card,
        .impact-card,
        .testimonial-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all cards
            const cards = document.querySelectorAll('.advantage-card, .impact-card, .testimonial-card');
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            // Counter animation for impact numbers
            const countUp = (element, target) => {
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        element.textContent = target;
                        clearInterval(timer);
                    } else {
                        if (target.toString().includes('%')) {
                            element.textContent = Math.floor(current) + '%';
                        } else if (target.toString().includes('K')) {
                            element.textContent = Math.floor(current) + 'K+';
                        } else if (target.toString().includes('.')) {
                            element.textContent = current.toFixed(1) + '%';
                        } else {
                            element.textContent = Math.floor(current);
                        }
                    }
                }, 16);
            };

            // Start counter animation when impact section is visible
            const impactObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const numbers = entry.target.querySelectorAll('.impact-number');
                        numbers.forEach(num => {
                            const text = num.textContent;
                            if (text.includes('%')) {
                                const value = parseFloat(text);
                                countUp(num, value);
                            } else if (text.includes('K')) {
                                const value = parseInt(text);
                                countUp(num, value);
                            }
                        });
                        impactObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            const impactSection = document.querySelector('.impact-section');
            if (impactSection) {
                impactObserver.observe(impactSection);
            }
        });
    </script>
@endsection
