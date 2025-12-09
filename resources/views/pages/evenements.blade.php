@extends('pages.layouts.template')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="hero-title">
                        Organisez vos <span class="highlight-text">Événements</span>
                    </h1>
                    <p class="hero-subtitle">Gérez tous vos événements religieux de manière simple et efficace</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Types d'Événements Section -->
    <section class="event-types-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12">
                    <h2 class="section-title">Types d'Événements</h2>
                </div>
            </div>
            <div class="row">
                <!-- Messes Quotidiennes -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="event-card event-card-yellow">
                        <div class="event-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h3>Messes Quotidiennes</h3>
                        <p class="event-description">Gérez vos messes récurrentes avec facilité et efficacité</p>
                        <ul class="event-features">
                            <li><i class="fas fa-check-circle"></i> Planification récurrente automatique</li>
                            <li><i class="fas fa-check-circle"></i> Rappels personnalisés</li>
                            <li><i class="fas fa-check-circle"></i> Suivi des assistants</li>
                            <li><i class="fas fa-check-circle"></i> Rapports hebdomadaires</li>
                        </ul>
                    </div>
                </div>

                <!-- Événements Spéciaux -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="event-card event-card-yellow">
                        <div class="event-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h3>Événements Spéciaux</h3>
                        <p class="event-description">Organisez vos événements religieux importants sans stress</p>
                        <ul class="event-features">
                            <li><i class="fas fa-check-circle"></i> Configuration avancée</li>
                            <li><i class="fas fa-check-circle"></i> Gestion des invitations</li>
                            <li><i class="fas fa-check-circle"></i> Confirmations en temps réel</li>
                            <li><i class="fas fa-check-circle"></i> Notifications de groupe</li>
                        </ul>
                    </div>
                </div>

                <!-- Événements Paroisses -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="event-card event-card-yellow">
                        <div class="event-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h3>Événements Paroisses</h3>
                        <p class="event-description">Traitez avec respect les occasions importantes de votre communauté</p>
                        <ul class="event-features">
                            <li><i class="fas fa-check-circle"></i> Accès prioritaire</li>
                            <li><i class="fas fa-check-circle"></i> Suivi personnalisé</li>
                            <li><i class="fas fa-check-circle"></i> Notifications urgentes</li>
                            <li><i class="fas fa-check-circle"></i> Confidentialité renforcée</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Processus d'Événement Section -->
    <section class="event-process-section section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12">
                    <h2 class="section-title">Processus d'Événement</h2>
                </div>
            </div>
            <div class="row">
                <!-- Planifier -->
                <div class="col-lg-12 mb-4">
                    <div class="process-item">
                        <div class="process-number">1</div>
                        <div class="process-content">
                            <h4>Planifier</h4>
                            <p>Définissez la date, l'heure et le type de votre événement</p>
                        </div>
                    </div>
                </div>

                <!-- Inviter -->
                <div class="col-lg-12 mb-4">
                    <div class="process-item">
                        <div class="process-number">2</div>
                        <div class="process-content">
                            <h4>Inviter</h4>
                            <p>Envoyez des invitations et gérez les réservations</p>
                        </div>
                    </div>
                </div>

                <!-- Confirmer -->
                <div class="col-lg-12 mb-4">
                    <div class="process-item">
                        <div class="process-number">3</div>
                        <div class="process-content">
                            <h4>Confirmer</h4>
                            <p>Recevez les confirmations de présence en temps réel</p>
                        </div>
                    </div>
                </div>

                <!-- Réussir -->
                <div class="col-lg-12 mb-4">
                    <div class="process-item">
                        <div class="process-number">4</div>
                        <div class="process-content">
                            <h4>Réussir</h4>
                            <p>Célébrez un événement bien organisé et mémorable</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section-events">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-12">
                    <h2>Commencez à Organiser</h2>
                    <p>Simplifiez la gestion de vos événements religieux</p>
                    <a href="#" class="btn btn-cta"><i class="fas fa-download"></i> Télécharger App</a>
                </div>
            </div>
        </div>
    </section>

    <style>
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

        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f1e8;
        }

        /* Hero Section */
        .hero-section {
            background-color: #f5f1e8;
            padding: 80px 0 60px;
            text-align: center;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 1rem;
        }

        .highlight-text {
            color: var(--primary-gold);
            position: relative;
            display: inline-block;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #666;
            font-weight: 400;
        }

        /* Section Padding */
        .section-padding {
            padding: 60px 0;
        }

        /* Section Title */
        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 2rem;
        }

        /* Event Types Section */
        .event-types-section {
            background-color: #fff;
        }

        .event-card {
            background: #fff;
            border-radius: 15px;
            padding: 40px 30px;
            text-align: left;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: 1px solid #e8e4d9;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .event-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #fff;
        }

        .event-card-bronze .event-icon {
            background-color: #8b6f47;
        }

        .event-card-yellow .event-icon {
            background-color: #d4a03a;
        }

        .event-card-red .event-icon {
            background-color: #c94e4e;
        }

        .event-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 1rem;
        }

        .event-description {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .event-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .event-features li {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
        }

        .event-features li i {
            font-size: 0.75rem;
            margin-right: 10px;
            margin-top: 4px;
            flex-shrink: 0;
        }

        .event-card-bronze .event-features li i {
            color: #8b6f47;
        }

        .event-card-yellow .event-features li i {
            color: #d4a03a;
        }

        .event-card-red .event-features li i {
            color: #c94e4e;
        }

        /* Event Process Section */
        .event-process-section {
            background-color: #f5f1e8;
        }

        .process-item {
            display: flex;
            align-items: flex-start;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            border: 1px solid #e8e4d9;
        }

        .process-item:hover {
            transform: translateX(5px);
        }

        .process-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #8b6f47;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-right: 20px;
        }

        .process-content h4 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 0.5rem;
        }

        .process-content p {
            font-size: 0.95rem;
            color: #666;
            margin: 0;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section-events {
            background-color: #fff;
            padding: 80px 0;
            text-align: center;
        }

        .cta-section-events h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 1rem;
        }

        .cta-section-events p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
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

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .event-card {
                margin-bottom: 1.5rem;
            }

            .process-item {
                margin-bottom: 1.5rem;
            }
        }
    </style>
@endsection
