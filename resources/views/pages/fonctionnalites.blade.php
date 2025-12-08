@extends('pages.layouts.template')
@section('content')
    <!-- Hero Section -->
    <section class="features-hero">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-10 mx-auto">
                    <h1 class="hero-title">Explorez nos <span class="text-highlight">Fonctionnalités</span></h1>
                    <p class="hero-subtitle">Découvrez tous les outils puissants d'E-messe pour gérer pour vos messes
                        efficacement.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid Section -->
    <section class="features-grid section-padding">
        <div class="container">
            <div class="row g-4">
                <!-- Feature Card 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="feature-card-title">Calendrier Intuitif</h3>
                        <p class="feature-card-description">Visualisez et gérez l’ensemble de vos messes sur un calendrier
                            interactif et facile à utiliser.</p>
                        <a href="#calendrier" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-user-group">‌</i>
                        </div>
                        <h3 class="feature-card-title">Gestion des Fidèles</h3>
                        <p class="feature-card-description">Suivez facilement les demandes des fidèles et gérez les
                            confirmations en quelques clics.</p>
                        <a href="#fideles" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-table-cells-large">‌</i>
                        </div>
                        <h3 class="feature-card-title">Tableau de bord</h3>
                        <p class="feature-card-description">Analysez vos statistiques avec des graphiques détaillés et des
                            rapports complets.</p>
                        <a href="#notifications" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-circle-check">‌</i>
                        </div>
                        <h3 class="feature-card-title">Validation Rapide</h3>
                        <p class="feature-card-description">Approuvez ou rejetez les demandes de messes en un clic, avec
                            notifications instantanées.</p>
                        <a href="#validation" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>


                <!-- Feature Card 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-bolt">‌</i>
                        </div>
                        <h3 class="feature-card-title">Notifications en Temps Réel</h3>
                        <p class="feature-card-description">Restez informé de tous les changements avec des notifications
                            intelligentes.</p>
                        <a href="#multiparoisse" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-shield">‌</i>
                        </div>
                        <h3 class="feature-card-title">Sécurité Garantie</h3>
                        <p class="feature-card-description">Vos données sont protégées avec les plus hauts standards de
                            sécurité.</p>
                        <a href="#securite" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Features Section -->
    <section class="features-detailed section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Fonctionnalités en Détail</h2>
                </div>
            </div>

            <!-- Feature Detail 1 - Calendrier Intuitif -->
            <div class="feature-detail" id="calendrier">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-1">
                        <div class="feature-detail-content">
                            <h3 class="feature-detail-title">Calendrier Intuitif</h3>
                            <p class="feature-detail-description">Visualisez et gérez l’ensemble de vos messes sur un
                                calendrier interactif et facile à utiliser.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Vue mensuelle, hebdomadaire et journalière</li>
                                <li><i class="fas fa-check-circle"></i> Ajout rapide de nouvelles messes</li>
                                <li><i class="fas fa-check-circle"></i> Filtrage par type de messe et paroisse</li>
                                <li><i class="fas fa-check-circle"></i> Synchronisation en temps réel</li>
                                <li><i class="fas fa-check-circle"></i> Exportation au format iCal</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-2">
                        <div class="feature-detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="icon-label">Illustration Calendrier intuitif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Detail 2 - Gestion des Fidèles -->
            <div class="feature-detail" id="fideles">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="feature-detail-icon">
                            <i class="fa-solid fa-user-group">‌</i>
                            <span class="icon-label">Proximité avec vos Fidèles</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-detail-content">
                            <h3 class="feature-detail-title">Gestion des Fidèles</h3>
                            <p class="feature-detail-description">Suivez chaque membre de votre communauté et gérez toutes
                                les demandes de messe avec simplicité et efficacité.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Base de données centralisée</li>
                                <li><i class="fas fa-check-circle"></i> Historique complet des messes</li>
                                <li><i class="fas fa-check-circle"></i> Notes privées et commentaires</li>
                                <li><i class="fas fa-check-circle"></i> Recherche avancée</li>
                                <li><i class="fas fa-check-circle"></i> Gestion des intentions personnelles</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Detail 3 - Calendrier Intuitif (repeat for demonstration) -->
            <div class="feature-detail" id="notifications">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="feature-detail-icon">
                            <i class="fa-solid fa-table-cells-large">‌</i>
                            <span class="icon-label">Toujours Informé en temps réel</span>
                        </div>
                    </div>
                    <div class="col-lg-6  order-lg-1">
                        <div class="feature-detail-content">
                            <h3 class="feature-detail-title">Calendrier Intuitif</h3>
                            <p class="feature-detail-description">Ne manquez plus jamais une messe importante grâce à notre
                                système de rappels automatiques et notifications personnalisées.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Alertes par email et SMS</li>
                                <li><i class="fas fa-check-circle"></i> Rappels d'anniversaires de décès</li>
                                <li><i class="fas fa-check-circle"></i> Notifications de validation</li>
                                <li><i class="fas fa-check-circle"></i> Alertes de nouvelles demandes</li>
                                <li><i class="fas fa-check-circle"></i> Préférences personnalisables</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">Prêt à Démarrer ?</h2>
                    <p class="cta-description">Rejoignez des centaines de paroisses qui utilisent déjà E-MESSE</p>
                    <a href="#" class="btn btn-cta"><i class="fas fa-download"></i> Télécharger App</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* ========================================
                                                                                                                       GLOBAL STYLES
                                                                                                                    ======================================== */
        :root {
            --primary-color: #D4A574;
            --primary-dark: #B8895F;
            --secondary-color: #2C3E50;
            --text-dark: #1A1A1A;
            --text-gray: #6B7280;
            --bg-light: #F9FAFB;
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

        /* ========================================
                                                                                                                       HERO SECTION
                                                                                                                    ======================================== */
        .features-hero {
            background: #f7f4eb;
            padding: 100px 0 80px;
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
            color: var(--primary-color);
            position: relative;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-gray);
            max-width: 700px;
            margin: 0 auto;
        }

        /* ========================================
                                                                                                                       SECTION UTILITIES
                                                                                                                    ======================================== */
        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3rem;
            position: relative;
        }

        /* ========================================
                                                                                                                       FEATURES GRID
                                                                                                                    ======================================== */
        .features-grid {
            background: var(--white);
        }

        .feature-card {
            background: var(--white);
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 40px 30px;
            height: 100%;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            transform: scaleX(0);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #FAF5F0 0%, #F5EBDC 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .feature-card:hover .feature-card-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .feature-card-icon i {
            font-size: 2rem;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .feature-card:hover .feature-card-icon i {
            color: var(--white);
        }

        .feature-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .feature-card-description {
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .feature-link {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .feature-link:hover {
            color: var(--primary-dark);
            gap: 12px;
        }

        .feature-link i {
            font-size: 0.875rem;
            transition: var(--transition);
        }

        /* ========================================
                                                                                                                       DETAILED FEATURES
                                                                                                                    ======================================== */
        .features-detailed {
            background: var(--bg-light);
        }

        .feature-detail {
            margin-bottom: 100px;
        }

        .feature-detail:last-child {
            margin-bottom: 0;
        }

        .feature-detail-content {
            padding: 20px 0;
        }

        .feature-detail-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .feature-detail-description {
            font-size: 1.1rem;
            color: var(--text-gray);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            padding: 12px 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1rem;
        }

        .feature-list i {
            color: var(--primary-color);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .feature-detail-icon {
            background: linear-gradient(135deg, #FAF5F0 0%, #F5EBDC 100%);
            border-radius: 24px;
            padding: 60px;
            text-align: center;
            position: relative;
            margin: 20px;
            border: 2px solid var(--primary-color);
            min-height: 350px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .feature-detail-icon i {
            font-size: 6rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .icon-label {
            display: block;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* ========================================
                                                                                                                       CTA SECTION
                                                                                                                    ======================================== */
        .cta-section {
            background: #faf4e6;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #000000;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .cta-description {
            font-size: 1.25rem;
            color: #787a7a;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .btn-cta {
            background: #d6bc85;
            color: #ffffff;
            padding: 16px 48px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: var(--transition);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
        }

        .btn-cta:hover {
            background: #d6bc85;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
        }

        /* ========================================
                                                                                                                       RESPONSIVE DESIGN
                                                                                                                    ======================================== */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .feature-detail-title {
                font-size: 1.75rem;
            }

            .feature-detail-icon {
                margin: 20px 0 40px;
                min-height: 280px;
                padding: 40px;
            }

            .feature-detail-icon i {
                font-size: 4rem;
            }

            .cta-title {
                font-size: 2rem;
            }

            .feature-detail {
                margin-bottom: 60px;
            }
        }

        @media (max-width: 768px) {
            .features-hero {
                padding: 60px 0 40px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-padding {
                padding: 60px 0;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .feature-card {
                padding: 30px 20px;
            }

            .cta-section {
                padding: 60px 0;
            }

            .cta-title {
                font-size: 1.75rem;
            }

            .cta-description {
                font-size: 1.1rem;
            }

            .btn-cta {
                padding: 14px 36px;
                font-size: 1rem;
            }
        }
    </style>
@endsection
