@extends('pages.layouts.template')
@section('content')
    <!-- Hero Section -->
    <section class="messe-hero">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-10 mx-auto">
                    <h1 class="hero-title">Gérez vos <span class="text-highlight">Messe Facilement</span> </h1>
                    <p class="hero-subtitle">Simplifiez la gestion de vos messes du debut à la fin</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Comment ça marche Section -->
    <section class="how-it-works-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="section-title-dark">Comment ça marche ?</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-icon-wrapper">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h4 class="step-title">Créer une Messe</h4>
                        <p class="step-description">Configurez les détails de votre messe en quelques minutes</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-icon-wrapper">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h4 class="step-title">Réserver les demandes</h4>
                        <p class="step-description">Les fidèles peuvent demander directement via l’application</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-icon-wrapper">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="step-title">Valider & Confirmer</h4>
                        <p class="step-description">Approuvez les demandes et notifiez instantanément</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="step-card">
                        <div class="step-icon-wrapper">
                            <i class="fa-solid fa-file-text">‌</i>
                        </div>
                        <h4 class="step-title">Générer les Rapports</h4>
                        <p class="step-description">Analysez les statistiques et créez des rapports</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cas d'Usage Section -->
    <section class="use-cases-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="section-title-dark">Cas d'Usage</h2>
                </div>
            </div>
            <div class="row g-4">
                <!-- Card 1: Messe de Noël -->
                <div class="col-lg-6">
                    <div class="use-case-card">
                        <h3 class="use-case-title">Messe de Noël</h3>
                        <p class="use-case-description">Gérez les réservations massives pour la messe de Noël avec
                            confirmation automatique
                        </p>
                        <hr>
                        <ul class="use-case-list">
                            <li><i class="fas fa-circle"></i>
                                Jusqu’à 500 + réservations</li>
                            <li><i class="fas fa-circle"></i>
                                Notifications automatiques</li>
                            <li><i class="fas fa-circle"></i>
                                Gestion des capacités</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 2: Mariage -->
                <div class="col-lg-6">
                    <div class="use-case-card">
                        <h3 class="use-case-title">Mariage</h3>
                        <p class="use-case-description">Organisez le mariage avec tous les détails spécifiques et
                            coordonnations</p>
                        <hr>
                        <ul class="use-case-list">
                            <li><i class="fas fa-circle"></i> Invitations personnalisées</li>
                            <li><i class="fas fa-circle"></i> Confirmations instantanées</li>
                            <li><i class="fas fa-circle"></i> Liste personnalisée</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 3: Funérailles -->
                <div class="col-lg-6">
                    <div class="use-case-card">
                        <h3 class="use-case-title">Funérailles</h3>
                        <p class="use-case-description">Facilite les arrangements funéraires avec respect et efficacité</p>
                        <hr>
                        <ul class="use-case-list">
                            <li><i class="fas fa-circle"></i>Notifications urgentes</li>
                            <li><i class="fas fa-circle"></i>Accès prioritaire</li>
                            <li><i class="fas fa-circle"></i>Suivi personnalisé</li>
                        </ul>
                    </div>
                </div>

                <!-- Card 4: Messes Quotidiennes -->
                <div class="col-lg-6">
                    <div class="use-case-card">
                        <h3 class="use-case-title">Messes Quotidiennes</h3>
                        <p class="use-case-description">Suivez les réservations récurrentes et automatisez le processus</p>
                        <hr>
                        <ul class="use-case-list">
                            <li><i class="fas fa-circle"></i>Planification récurrente</li>
                            <li><i class="fas fa-circle"></i>Rappels automatiques</li>
                            <li><i class="fas fa-circle"></i>Statistiques mensuelles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact en chiffres Section -->
    <section class="impact-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="section-title-dark">Impact en chiffres</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="impact-card">
                        <h3 class="impact-number">75%</h3>
                        <p class="impact-label">Gain de temps<br>par messe planifiée</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="impact-card">
                        <h3 class="impact-number">98%</h3>
                        <p class="impact-label">Satisfaction<br>fidèles</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="impact-card">
                        <h3 class="impact-number">50K+</h3>
                        <p class="impact-label">Messes gérées<br>chaque année</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="impact-card">
                        <h3 class="impact-number">99.9%</h3>
                        <p class="impact-label">Taux de<br>disponibilité</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="final-cta-section section-padding">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="cta-title">Commencez à Gérer vos Messes Aujourd'hui</h2>
                    <p class="cta-subtitle">Rejoignez les centaines de paroisses qui font confiance à E-MESSE</p>
                    <a href="#" class="btn btn-cta"><i class="fas fa-download"></i> Télécharger l'App</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* ========================================
                                                                                                                               GLOBAL STYLES
                                                                                                                            ======================================== */
        :root {
            --primary-gold: #D4A574;
            --primary-dark: #B8895F;
            --text-dark: #1A1A1A;
            --text-gray: #6B7280;
            --bg-cream: #F5F5DC;
            --bg-light-cream: #FAF8F3;
            --white: #FFFFFF;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.16);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .section-padding {
            padding: 80px 0;
        }

        /* ========================================
                                                                                                                               HERO SECTION
                                                                                                                            ======================================== */
        .messe-hero {
            background: #f7f4eb;
            padding: 120px 0 100px;
            text-align: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .text-highlight {
            color: var(--primary-gold);
            position: relative;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: var(--text-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        /* ========================================
                                                                                                                               SECTION TITLES
                                                                                                                            ======================================== */
        .section-title-dark {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3rem;
        }

        /* ========================================
                                                                                                                               HOW IT WORKS SECTION
                                                                                                                            ======================================== */
        .how-it-works-section {
            background: var(--white);
        }

        .step-card {
            text-align: center;
            padding: 30px 20px;
            transition: var(--transition);
        }

        .step-icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FFF5E8 0%, #F5EBDC 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            transition: var(--transition);
        }

        .step-icon-wrapper i {
            font-size: 2.5rem;
            color: var(--primary-gold);
            transition: var(--transition);
        }

        .step-card:hover .step-icon-wrapper {
            background: linear-gradient(135deg, var(--primary-gold), var(--primary-dark));
            transform: scale(1.1);
        }

        .step-card:hover .step-icon-wrapper i {
            color: var(--white);
        }

        .step-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .step-description {
            color: var(--text-gray);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* ========================================
                                                                                                                               USE CASES SECTION
                                                                                                                            ======================================== */
        .use-cases-section {
            background: #f0f0f0;
        }

        .use-case-card {
            background: var(--white);
            /* border: 1px solid #E5E7EB; */
            border-radius: 16px;
            border: 1px solid #b9b0b0ff;
            padding: 40px 30px;
            height: 100%;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .use-case-card:hover {
            background: var(--white);

        }

        .use-case-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .use-case-description {
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .use-case-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .use-case-list li {
            padding: 8px 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .use-case-list i {
            color: var(--primary-gold);
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* ========================================
                                                                                                                               IMPACT SECTION
                                                                                                                            ======================================== */
        .impact-section {
            background: var(--white);
        }

        .impact-card {
            background: var(--bg-light-cream);
            border-radius: 16px;
            padding: 50px 30px;
            text-align: center;
            transition: var(--transition);
        }

        .impact-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .impact-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-gold);
            margin-bottom: 1rem;
            line-height: 1;
        }

        .impact-label {
            color: var(--text-gray);
            font-size: 1rem;
            line-height: 1.5;
            margin: 0;
        }

        /* ========================================
                                                                                                                               FINAL CTA SECTION
                                                                                                                            ======================================== */
        .final-cta-section {
            background: var(--bg-cream);
            position: relative;
            overflow: hidden;
        }

        .final-cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(212, 165, 116, 0.1);
            border-radius: 50%;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            color: var(--text-gray);
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .btn-cta {
            background: var(--primary-gold);
            color: var(--white);
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

        /* ========================================
                                                                                                                               RESPONSIVE DESIGN
                                                                                                                            ======================================== */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .section-title-dark {
                font-size: 2rem;
            }

            .cta-title {
                font-size: 2rem;
            }

            .impact-number {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .messe-hero {
                padding: 80px 0 60px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-padding {
                padding: 60px 0;
            }

            .section-title-dark {
                font-size: 1.75rem;
            }

            .cta-title {
                font-size: 1.75rem;
            }

            .cta-subtitle {
                font-size: 1.1rem;
            }

            .btn-cta {
                padding: 14px 36px;
                font-size: 1rem;
            }

            .impact-number {
                font-size: 2rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements on scroll
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

            const animatedElements = document.querySelectorAll('.step-card, .use-case-card, .impact-card');
            animatedElements.forEach(el => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
@endsection
