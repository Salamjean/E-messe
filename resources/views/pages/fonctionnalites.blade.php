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
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="feature-card-title">Calendrier Intuitif</h3>
                        <p class="feature-card-description">Visualisez et gérez l'ensemble de vos messes sur un calendrier
                            interactif et facile à utiliser.</p>
                        <a href="#calendrier" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 2 -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="feature-card">
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
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-table-cells-large"></i>
                        </div>
                        <h3 class="feature-card-title">Tableau de bord</h3>
                        <p class="feature-card-description">Analysez vos statistiques avec des graphiques détaillés et des
                            rapports complets.</p>
                        <a href="#notifications" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 4 -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="feature-card">
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
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h3 class="feature-card-title">Notifications en Temps Réel</h3>
                        <p class="feature-card-description">Restez informé de tous les changements avec des notifications
                            intelligentes.</p>
                        <a href="#multiparoisse" class="feature-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Feature Card 6 -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="feature-card">
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

    <!-- Detailed Features Section -->
    <section class="features-detailed section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Fonctionnalités en Détail</h2>
                </div>
            </div>

            <!-- Feature Detail 1 - Calendrier Intuitif -->
            <div class="feature-detail mb-5" id="calendrier">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6 order-lg-1 order-2">
                        <div class="feature-detail-content">
                            <h3 class="feature-detail-title">Calendrier Intuitif</h3>
                            <p class="feature-detail-description">Visualisez et gérez l'ensemble de vos messes sur un
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
                    <div class="col-lg-6 order-lg-2 order-1">
                        <div class="container-fluid">
                            <div class="feat-card d-flex flex-column flex-md-row align-items-center shadow position-relative"
                                style="border: 1px solid #c2a16d; border-radius: 20px; padding: 30px;">
                                <!-- Texte -->
                                <div class="feature-text col-lg-6 col-md-6 p-3 p-md-4">
                                    <h4 class="text-primar">
                                        Processus de Validation de messe par le fidèle lui-même
                                        depuis son téléphone
                                    </h4>
                                </div>
                                <!-- Image téléphone -->
                                <div class="feature-image col-lg-6 col-md-6 text-center position-relative overflow-visible">
                                    <img src="{{ asset('assets/fonctionnalite/img/demande_phone.png') }}"
                                        alt="Validation Mobile" class="phone-img img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Detail 2 - Gestion des Fidèles -->
            <div class="feature-detail mb-5" id="fideles">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6 order-lg-1 order-1">
                        <div class="container-fluid">
                            <div class="feat-card d-flex flex-column flex-md-row align-items-center shadow position-relative"
                                style="border: 1px solid #c2a16d; border-radius: 20px; padding: 30px;">
                                <!-- Image téléphone -->
                                <div class="feature-image col-lg-6 col-md-6 text-center position-relative overflow-visible">
                                    <img src="{{ asset('assets/fonctionnalite/img/dash_photo.png') }}"
                                        alt="Validation Mobile" class="phone-img img-fluid">
                                </div>
                                <!-- Texte -->
                                <div class="feature-text col-lg-6 col-md-6 p-3 p-md-4">
                                    <h4 class="text-primar">
                                        Processus de Validation de messe par le fidèle lui-même
                                        depuis son téléphone
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-2 order-2">
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

            <!-- Feature Detail 3 - Notifications -->
            <div class="feature-detail mb-5" id="notifications">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6 order-lg-1 order-2">
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
                    <div class="col-lg-6 order-lg-2 order-1">
                        <div class="container-fluid">
                            <div class="feat-card d-flex flex-column flex-md-row align-items-center shadow position-relative"
                                style="border: 1px solid #c2a16d; border-radius: 20px; padding: 30px;">
                                <!-- Image téléphone -->
                                <div
                                    class="feature-image col-lg-6 col-md-6 text-center position-relative overflow-visible">
                                    <img src="{{ asset('assets/fonctionnalite/img/Free_MacBook_Pro_3.png') }}"
                                        alt="Validation Mobile" class="phone-img img-fluid">
                                </div>
                                <!-- Texte -->
                                <div class="feature-text col-lg-6 col-md-6 p-3 p-md-4">
                                    <h4 class="text-primar">
                                        Processus de Validation de messe par le fidèle lui-même
                                        depuis son téléphone
                                    </h4>
                                </div>
                            </div>
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
            line-height: 1.2;
        }

        .text-highlight {
            color: var(--primary-color);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-gray);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            line-height: 1.2;
        }

        /* ========================================
                                FEATURES GRID CARDS
                                ======================================== */
        .feature-card {
            background: var(--white);
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 30px 20px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 300px;
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
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FAF5F0 0%, #F5EBDC 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .feature-card:hover .feature-card-icon {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .feature-card-icon i {
            font-size: 1.75rem;
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
            line-height: 1.3;
        }

        .feature-card-description {
            color: var(--text-gray);
            font-size: 0.95rem;
            margin-bottom: auto;
            line-height: 1.6;
            padding-bottom: 1rem;
            flex-grow: 1;
        }

        .feature-link {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: auto;
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
            margin-bottom: 80px;
            scroll-margin-top: 80px;
        }

        .feature-detail:last-child {
            margin-bottom: 0;
        }

        .feature-detail-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
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
            padding: 8px 0;
            color: var(--text-dark);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            line-height: 1.5;
        }

        .feature-list i {
            color: var(--primary-color);
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Card spécifique "Validation" */
        .feat-card {
            background: #fff;
            background: linear-gradient(to bottom, #e8dfd2, #c8e3ed);
            border-radius: 20px;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            position: relative;
            margin: 0 auto;
        }

        .feature-text h4 {
            color: #4b9aa5;
            font-size: 18px;
            line-height: 1.4;
            margin: 0;
        }

        .phone-img {
            width: 100%;
            max-width: 250px;
            height: auto;
            display: block;
            margin: 0 auto;
            transition: var(--transition);
        }

        .feat-card:hover .phone-img {
            transform: scale(1.05);
        }

        /* Empêche le card de couper l'image */
        .feature-image,
        .feat-card {
            overflow: visible !important;
            position: relative;
        }

        .feature-image-container {
            background: linear-gradient(135deg, #FAF5F0 50%, #F5EBDC 50%);
            border-radius: 24px;
            width: 100% !important;
            padding: 80px;
            text-align: center;
            position: relative;
            border: 2px solid #f0e6d9;
            min-height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .phone-img {
            width: 100%;
            /* <<< beaucoup plus large */
            max-width: 550px;
            /* limite max optionnelle */
            /* height: 3000px; */
            /* garde les proportions */
            position: absolute;
            top: -150px;
            /* <<< dépasse plus haut */
            right: -20px;
            /* décale un peu hors du card */
            z-index: 20;
            transform-origin: top right;
            transition: transform 0.3s ease;
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
            line-height: 1.2;
        }

        .cta-description {
            font-size: 1.25rem;
            color: #787a7a;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
            line-height: 1.6;
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
            font-size: 1rem;
        }

        .btn-cta:hover {
            background: white;
            border: 2px solid #d6bc85;
            color: #d6bc85;
            transform: translateY(-2px);
        }

        /* ========================================
                                RESPONSIVE
                                ======================================== */
        @media (max-width: 1200px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2.25rem;
            }

            .feature-detail-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 992px) {
            .features-hero {
                padding: 80px 0 60px;
            }

            .hero-title {
                font-size: 2.25rem;
            }

            .section-padding {
                padding: 60px 0;
            }

            .feature-detail {
                text-align: center;
                margin-bottom: 60px;
            }

            .feature-list li {
                justify-content: center;
            }

            .feat-card {
                margin-bottom: 30px;
            }

            .feature-image {
                margin-top: 20px;
            }

            .cta-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .features-hero {
                padding: 60px 0 40px;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 1.75rem;
                margin-bottom: 2rem;
            }

            .section-padding {
                padding: 40px 0;
            }

            .feature-card {
                padding: 25px 15px;
                min-height: 280px;
            }

            .feature-card-icon {
                width: 50px;
                height: 50px;
                margin-bottom: 1rem;
            }

            .feature-card-icon i {
                font-size: 1.5rem;
            }

            .feature-card-title {
                font-size: 1.1rem;
            }

            .feature-card-description {
                font-size: 0.9rem;
            }

            .feature-detail-title {
                font-size: 1.5rem;
            }

            .feature-detail-description {
                font-size: 1rem;
            }

            .feature-text h4 {
                font-size: 16px;
            }

            .phone-img {
                max-width: 200px;
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
                padding: 12px 32px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .feature-card {
                min-height: 250px;
            }

            .feature-card-title {
                font-size: 1rem;
            }

            .feature-detail-title {
                font-size: 1.25rem;
            }

            .feature-list li {
                font-size: 0.9rem;
                padding: 6px 0;
            }

            .feat-card {
                padding: 15px;
                border-radius: 15px;
            }

            .feature-text h4 {
                font-size: 14px;
            }

            .phone-img {
                max-width: 150px;
            }

            .cta-title {
                font-size: 1.5rem;
            }

            .cta-description {
                font-size: 1rem;
            }
        }

        /* Améliorations pour très petits écrans */
        @media (max-width: 375px) {
            .feature-card {
                padding: 20px 15px;
            }

            .feature-card-icon {
                width: 45px;
                height: 45px;
            }

            .feature-card-icon i {
                font-size: 1.25rem;
            }

            .feature-card-title {
                font-size: 0.95rem;
            }

            .feature-card-description {
                font-size: 0.85rem;
            }

            .btn-cta {
                padding: 10px 24px;
                font-size: 0.85rem;
            }
        }

        /* Correction pour l'ordre des éléments sur mobile */
        @media (max-width: 767px) {
            .feature-detail .row>div {
                margin-bottom: 30px;
            }

            .feat-card {
                flex-direction: column !important;
                text-align: center;
            }

            .feature-text {
                order: 2;
                margin-top: 20px;
            }

            .feature-image {
                order: 1;
            }
        }
    </style>
@endsection
