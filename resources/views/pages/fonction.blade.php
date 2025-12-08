@extends('pages.layouts.template')

@section('content')
    <!-- ========================================
           HERO SECTION
        ======================================== -->
    <section class="features-hero">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-10 mx-auto">
                    <h1 class="hero-title">Explorez nos <span class="text-highlight">Fonctionnalités</span></h1>
                    <p class="hero-subtitle">Découvrez tous les outils puissants d'E-messe pour gérer vos messes
                        efficacement.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
           FEATURES GRID SECTION
        ======================================== -->
    <section class="features-grid section-padding">
        <div class="container">
            <div class="row g-4">
                <!-- Feature Card 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
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
                    <div class="feature-card h-100">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <h3 class="feature-card-title">Gestion des Fidèles</h3>
                        <p class="feature-card-description">Suivez facilement les demandes des fidèles et gérez les
                            confirmations en quelques clics.</p>
                        <a href="#fideles" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-table-cells-large"></i>
                        </div>
                        <h3 class="feature-card-title">Tableau de bord</h3>
                        <p class="feature-card-description">Analysez vos statistiques avec des graphiques détaillés et des
                            rapports complets.</p>
                        <a href="#dashboard" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h3 class="feature-card-title">Validation Rapide</h3>
                        <p class="feature-card-description">Approuvez ou rejetez les demandes de messes en un clic, avec
                            notifications instantanées.</p>
                        <a href="#validation" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h3 class="feature-card-title">Notifications en Temps Réel</h3>
                        <p class="feature-card-description">Restez informé de tous les changements avec des notifications
                            intelligentes.</p>
                        <a href="#notifications" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card h-100">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-shield"></i>
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

    <!-- ========================================
           DETAILED FEATURES SECTION
        ======================================== -->
    <section class="features-detailed section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Fonctionnalités en Détail</h2>
                </div>
            </div>

            <!-- Detail 1: Calendrier Intuitif -->
            <div class="feature-detail" id="calendrier">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-1 mb-4 mb-lg-0">
                        <div class="feature-detail-content">
                            <h3 class="feature-detail-title">Calendrier Intuitif</h3>
                            <p class="feature-detail-description">Visualisez et gérez l’ensemble de vos messes sur un
                                calendrier interactif et facile à utiliser.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Vue mensuelle, hebdomadaire et journalière</li>
                                <li><i class="fas fa-check-circle"></i> Ajout rapide de nouvelles messes</li>
                                <li><i class="fas fa-check-circle"></i> Filtrage par type de messe et paroisse</li>
                                <li><i class="fas fa-check-circle"></i> Synchronisation en temps réel</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-2">
                        <!-- Image Standardisée pour le calendrier -->
                        <div class="feature-image-container">
                            <!-- Remplacez l'icone par une image de calendrier si disponible -->
                            <i class="fas fa-calendar-alt feature-big-icon"></i>
                            <div class="gradient-overlay blue-gradient"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail 2: Validation Rapide (Le design Phone Card convient mieux ici) -->
            <div class="feature-detail" id="validation">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                        <div class="feature-detail-content ps-lg-4">
                            <h3 class="feature-detail-title">Validation Simplifiée</h3>
                            <p class="feature-detail-description">Gérez les demandes de messes entrantes avec une interface
                                conçue pour la rapidité.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Validation en un clic</li>
                                <li><i class="fas fa-check-circle"></i> Notification automatique au fidèle</li>
                                <li><i class="fas fa-check-circle"></i> Gestion des conflits d'horaires</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <!-- Design Spécifique Phone Card -->
                        <div class="container d-flex justify-content-center">
                            <div class="feat-card d-flex flex-wrap align-items-center shadow">
                                <div class="feature-text col-lg-6 col-md-6 p-4">
                                    <h4 class="fw-bold text-primary">Processus de Validation</h4>
                                    <p class="text-muted m-0">Approbation instantanée depuis le tableau de bord.</p>
                                </div>
                                <div class="feature-image col-lg-6 col-md-6 text-center position-relative">
                                    <img src="{{ asset('assets/fonctionnalite/img/demande_phone.png') }}"
                                        alt="Validation Mobile" class="phone-img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail 3: Gestion des Fidèles -->
            <div class="feature-detail" id="fideles">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-1 mb-4 mb-lg-0">
                        <div class="feature-detail-content">
                            <h3 class="feature-detail-title">Gestion des Fidèles</h3>
                            <p class="feature-detail-description">Suivez chaque membre de votre communauté et gérez toutes
                                les demandes de messe avec simplicité et efficacité.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Base de données centralisée</li>
                                <li><i class="fas fa-check-circle"></i> Historique complet des messes</li>
                                <li><i class="fas fa-check-circle"></i> Notes privées et commentaires</li>
                                <li><i class="fas fa-check-circle"></i> Gestion des intentions personnelles</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-2">
                        <div class="feature-image-container">
                            <img src="{{ asset('assets/fonctionnalite/img/logo_phone.png') }}" alt="Gestion Fidèles"
                                class="img-fluid floating-img">
                            <div class="gradient-overlay"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail 4: Notifications (Corrigé le titre qui était 'Calendrier') -->
            <div class="feature-detail" id="notifications">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                        <div class="feature-detail-content ps-lg-4">
                            <h3 class="feature-detail-title">Notifications Intelligentes</h3>
                            <p class="feature-detail-description">Ne manquez plus jamais une messe importante grâce à notre
                                système de rappels automatiques et notifications personnalisées.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-check-circle"></i> Alertes par email et SMS</li>
                                <li><i class="fas fa-check-circle"></i> Rappels d'anniversaires de décès</li>
                                <li><i class="fas fa-check-circle"></i> Alertes de nouvelles demandes</li>
                                <li><i class="fas fa-check-circle"></i> Préférences personnalisables</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="feature-image-container">
                            <img src="{{ asset('assets/fonctionnalite/img/Free_MacBook_Pro_3.png') }}" alt="Notifications"
                                class="img-fluid">
                            <div class="gradient-overlay blue-gradient"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================
           CTA SECTION
        ======================================== -->
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
               VARIABLES & RESET
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
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.16);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
        }

        /* ========================================
               HERO & HEADERS
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
        }

        .text-highlight {
            color: var(--primary-color);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-gray);
            max-width: 700px;
            margin: 0 auto;
        }

        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
        }

        /* ========================================
               FEATURES GRID CARDS
            ======================================== */
        .feature-card {
            background: var(--white);
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 40px 30px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
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
            margin-bottom: 1rem;
        }

        .feature-card-description {
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: auto;
            line-height: 1.7;
            padding-bottom: 1rem;
        }

        .feature-link {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .feature-link:hover {
            color: var(--primary-dark);
            gap: 12px;
        }

        /* ========================================
               DETAILED FEATURES
            ======================================== */
        .features-detailed {
            background: var(--bg-light);
        }

        .feature-detail {
            margin-bottom: 100px;
            scroll-margin-top: 100px;
            /* Pour l'ancre */
        }

        .feature-detail:last-child {
            margin-bottom: 0;
        }

        .feature-detail-title {
            font-size: 2rem;
            font-weight: 700;
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
        }

        .feature-list li {
            padding: 10px 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .feature-list i {
            color: var(--primary-color);
        }

        /* Conteneur d'image standard */
        .feature-image-container {
            background: linear-gradient(135deg, #FAF5F0 0%, #F5EBDC 100%);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            position: relative;
            border: 2px solid #f0e6d9;
            min-height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-big-icon {
            font-size: 8rem;
            color: rgba(212, 165, 116, 0.3);
        }

        /* Card spécifique "Validation" (Le design complexe) */
        .feat-card {
            background: #fff;
            /* Fallback */
            background: linear-gradient(to bottom, #e8dfd2, #c8e3ed);
            border-radius: 25px;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            position: relative;
        }

        .feature-text h4 {
            color: #4b9aa5;
            font-size: 20px;
        }

        .feature-text p {
            color: #4b9aa5;
            font-size: 16px;
        }

        .phone-img {
            width: 100%;
            max-width: 250px;
            transform: rotate(5deg);
            transition: var(--transition);
        }

        .feat-card:hover .phone-img {
            transform: rotate(0deg) scale(1.05);
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

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
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
            border: none;
            display: inline-block;
            transition: var(--transition);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
            text-decoration: none;
        }

        .btn-cta:hover {
            background: #d6bc85;
            color: #000;
            transform: translateY(-2px);
        }

        /* ========================================
               RESPONSIVE
            ======================================== */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .feature-detail {
                text-align: center;
            }

            .feature-list li {
                justify-content: center;
            }

            .feat-card {
                flex-direction: column;
                text-align: center;
            }

            .feature-image {
                margin-top: 20px;
            }
        }

        @media (max-width: 768px) {
            .features-hero {
                padding: 60px 0;
            }

            .hero-title {
                font-size: 2rem;
            }

            .section-padding {
                padding: 60px 0;
            }

            .cta-title {
                font-size: 1.75rem;
            }
        }
    </style>
@endsection
