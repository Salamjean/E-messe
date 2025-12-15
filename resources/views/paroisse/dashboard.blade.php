@extends('paroisse.layouts.template')


@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/paroiStyle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard_paroisse.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    {{-- CORRECTION 3: CSS pour harmoniser les inputs du modal et espacer les boutons --}}
    <style>

    </style>
@endpush

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="modern-dashboard">
        <!-- En-tête : Recherche et Bouton -->
        <div class="search-add-container d-flex justify-content-end">
            <a href="{{ route('event.index') }}" class="add-btn-gold text-decoration-none mx-3"
                style="margin-left:10px; margin-right:10px;">
                <i class="fas fa-plus"></i>
                <span>Événements</span>
            </a>
        </div>

        <!-- Cartes de statistiques -->
        <div class="row stats-row">
            <!-- Carte 1 : En attente (Jaune) -->
            <div class="col-custom">
                <div class="stat-card card-yellow shadow-sm">
                    <h6 class="card-title">Demandes en attente</h6>
                    <div class="card-bottom">
                        <span class="stat-number">{{ $pendingDemandes }}</span>
                        <div class="stat-trend">
                            <!-- Calcul du pourcentage simple -->
                            <span>{{ $totalDemandes > 0 ? round(($pendingDemandes / $totalDemandes) * 100) : 0 }}%</span>
                            <i class="fas fa-arrow-trend-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 2 : Célébrés (Vert) -->
            <div class="col-custom">
                <div class="stat-card card-green shadow-sm">
                    <h6 class="card-title">Messe Célébrés</h6>
                    <div class="card-bottom">
                        <span class="stat-number">{{ $celebratedDemandes }}</span>
                        <div class="stat-trend">
                            <span>{{ $totalDemandes > 0 ? round(($celebratedDemandes / $totalDemandes) * 100) : 0 }}%</span>
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 3 : Confirmé (Bleu) -->
            <div class="col-custom">
                <div class="stat-card card-blue shadow-sm">
                    <h6 class="card-title">Messe Confirmé</h6>
                    <div class="card-bottom">
                        <span class="stat-number">{{ $confirmedDemandes }}</span>
                        <div class="stat-trend">
                            <span>+{{ $totalDemandes > 0 ? round(($confirmedDemandes / $totalDemandes) * 100) : 0 }}%</span>
                            <i class="fas fa-arrow-trend-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 4 : Montant (Orange) -->
            <div class="col-custom">
                <div class="stat-card card-orange shadow-sm">
                    <h6 class="card-title">Montant demande</h6>
                    <div class="card-bottom">
                        <span class="stat-number">{{ number_format($totalOffrandes, 0, ',', ' ') }}F</span>
                        <div class="stat-trend">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 5 : Portefeuille (Violet) -->
            <div class="col-custom">
                <div class="stat-card card-purple shadow-sm">
                    <h6 class="card-title">Portefeuille</h6>
                    <div class="card-bottom">
                        <span class="stat-number">{{ number_format($soldeDisponible, 0, ',', ' ') }}F</span>
                        <div class="stat-trend">
                            <span>Disponible</span>
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <!-- Lien pour le retrait -->
                    <a href="{{ route('paroisse.retrait.create') }}" class="stretched-link"></a>
                </div>
            </div>
        </div><br>

        <div class="charts-section">
            <!-- Carte 1 : Répartition (Doughnut) -->
            <div class="chart-card slide-in-left">
                <div class="chart-header">
                    <h3>Répartition des demandes</h3>
                </div>
                <div class="chart-body-flex">
                    <div class="chart-wrapper-doughnut">
                        <canvas id="demands-chart"></canvas>
                    </div>
                    <!-- Légende personnalisée (Reflète les couleurs du JS) -->
                    <div class="chart-legend-custom">
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #f5c773"></span>
                            <span class="legend-text">En attente ({{ $pendingDemandes }})</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #87e0ab"></span>
                            <span class="legend-text">Célébrées ({{ $celebratedDemandes }})</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #6487d1"></span>
                            <span class="legend-text">Confirmées ({{ $confirmedDemandes }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 2 : Évolution (Line) -->
            <div class="chart-card slide-in-right">
                <div class="chart-header line-header">
                    <div class="header-text">
                        <h3>Évolution mensuelle des demandes</h3>
                        <span class="subtitle">Total demandes cette année : {{ array_sum($chartDataCurrentYear) }}</span>
                    </div>
                    <div class="chart-legend-top">
                        <div class="legend-badge active">Cette année</div>
                        <div class="legend-badge">Année dernière</div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="demands-evolution-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section Actions Rapides -->
        <div class="quick-actions-container">
            <h2 class="section-title">Action rapide</h2>

            <div class="actions-grid">
                <!-- Bouton 1 : Valider -->
                <a href="{{ route('demandes.messes.validate') }}" class="action-card card-green">
                    <div class="card-top">
                        <div class="icon-circle">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <div class="card-bottom">
                        <span>Valider demandes</span>
                    </div>
                </a>

                <!-- Bouton 2 : Confirmées -->
                <a href="{{ route('demandes.messes.index') }}" class="action-card card-gold">
                    <div class="card-top">
                        <div class="icon-circle">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                    <div class="card-bottom">
                        <span>Demande confirmées</span>
                    </div>
                </a>

                <!-- Bouton 3 : Ajouter Offrande -->
                <a href="{{ route('paroisse.offrande') }}" class="action-card card-pink">
                    <div class="card-top">
                        <div class="icon-circle">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                    <div class="card-bottom">
                        <span>Ajouter le montant des messes</span>
                    </div>
                </a>

                <!-- Bouton 4 : Historique -->
                <a href="{{ route('demandes.messes.history') }}" class="action-card card-blue">
                    <div class="card-top">
                        <div class="icon-circle">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                    <div class="card-bottom">
                        <span>Historique</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="dashboard-content-wrapper">
            <!-- SECTION GAUCHE : Prochaines Messes -->
            <div class="dashboard-card section-left slide-in-up">
                <div class="card-header">
                    <h2>Prochaines messes à célébrer</h2>
                    <a href="{{ route('demandes.messes.index') }}" class="link-view-all">Voir tout</a>
                </div>

                <div class="card-body">
                    @if (isset($upcomingMessess) && $upcomingMessess->count() > 0)
                        <div class="messes-list-container">
                            @foreach ($upcomingMessess as $messe)
                                <div class="messe-row">
                                    <div class="date-box">
                                        <span
                                            class="d-day">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d') }}</span>
                                        <span
                                            class="d-month">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('M') }}</span>
                                    </div>
                                    <div class="info-box">
                                        <h4>{{ \Illuminate\Support\Str::limit($messe->type_intention, 30) }}</h4>
                                        <p class="author">Par : {{ $messe->nom_demandeur }}</p>
                                        <div class="meta-tags">
                                            <span class="tag-time"><i class="far fa-clock"></i>
                                                {{ $messe->heure_souhaitee }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="calendar-icon">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <h3>Aucune Messe prévue</h3>
                            <p>Ajoutez un évènement pour démarrer</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SECTION DROITE : Dernière Demande -->
            <div class="dashboard-card section-right slide-in-up delay-100">
                <div class="card-header">
                    <h2>Dernière demande de messe</h2>
                </div>

                <div class="card-body">
                    @if (isset($latestOffrandes) && $latestOffrandes->count() > 0)
                        @php
                            $latest = $latestOffrandes->first();
                        @endphp
                        <div class="latest-request-card">
                            <div class="request-info">
                                <h3 class="requester-name">{{ $latest->nom_demandeur }}</h3>
                                <p class="request-intention">
                                    <span class="label">Intention :</span>
                                    {{ \Illuminate\Support\Str::limit($latest->type_intention, 50) }}
                                </p>
                                <p class="request-date">
                                    Reçu le {{ $latest->created_at->format('d F Y') }}
                                </p>
                            </div>
                            <div class="request-price">
                                {{ number_format($latest->montant_offrande, 0, ',', ' ') }} F
                            </div>
                        </div>
                    @else
                        <div class="empty-simple">
                            <p>Aucune demande récente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <script>
        // Empêche la sélection de dates passées
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            // Format YYYY-MM-DDTHH:MM pour datetime-local
            const formattedNow = now.toISOString().slice(0, 16);

            document.getElementById('date_debut').setAttribute('min', formattedNow);
            document.getElementById('date_fin').setAttribute('min', formattedNow);
        });
    </script>

    <style>
        .modal-dialog {
            max-width: 65%;
            /* largeur jusqu'à 90% de l'écran */
            width: auto;
            /* s'adapte au contenu si nécessaire */
            height: 80vh;
            /* hauteur jusqu'à 80% de la fenêtre */
            display: flex;
            align-items: right;
            /* centre verticalement */
        }
    </style>


    {{-- Inclusion du modal séparé --}}
    @include('paroisse.event.modal.event')


    @push('js')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        {{-- <script src="{{ asset('DataTables/dataTables.min.js') }}"></script> --}}
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('js/event.js') }}"></script>

        <script>
            // --- Définition des routes pour JS (inchangé) ---
            window.eventRoutes = {
                data: "{{ route('event.data') }}",
                show: "{{ route('event.show', ':id') }}",
                store: "{{ route('event.store') }}",
                update: "{{ route('event.update', ':id') }}",
                destroy: "{{ route('event.destroy', ':id') }}",
                csrf: "{{ csrf_token() }}"
            };
        </script>
    @endpush

    <!-- Scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. GRAPHIQUE ROND (Doughnut) : Répartition des statuts ---
            const ctxDoughnut = document.getElementById('demands-chart').getContext('2d');

            // Récupération des variables PHP
            const pendingCount = {{ $pendingDemandes }};
            const celebratedCount = {{ $celebratedDemandes }};
            const confirmedCount = {{ $confirmedDemandes }};

            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: ['En attente', 'Célébrées', 'Confirmées'],
                    datasets: [{
                        // Données injectées depuis le contrôleur
                        data: [pendingCount, celebratedCount, confirmedCount],
                        backgroundColor: ['#f5c773', '#87e0ab', '#6487d1'], // Jaune, Vert, Bleu
                        borderWidth: 0,
                        borderRadius: 20,
                        spacing: 10,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%', // Style anneau fin
                    plugins: {
                        legend: {
                            display: false // On utilise la légende HTML custom
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    let value = context.parsed;
                                    // Ajout du calcul de pourcentage
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = total > 0 ? Math.round((value / total) * 100) +
                                        '%' : '0%';
                                    return label + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    }
                }
            });

            // --- 2. GRAPHIQUE LIGNE (Line) : Évolution par nombre de demandes ---
            const ctxLine = document.getElementById('demands-evolution-chart').getContext('2d');
            const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

            // Injection des tableaux PHP (Jan à Déc)
            const currentYearData = @json($chartDataCurrentYear);
            const lastYearData = @json($chartDataLastYear);

            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Cette année',
                        data: currentYearData,
                        borderColor: '#6487d1', // Bleu
                        backgroundColor: 'rgba(100, 135, 209, 0.1)', // Bleu transparent
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 3
                    }, {
                        label: 'Année dernière',
                        data: lastYearData,
                        borderColor: '#f5c773', // Jaune/Orange
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5], // Ligne pointillée
                        tension: 0.3,
                        fill: false,
                        pointRadius: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Légende HTML au dessus
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            // --- CORRECTION ICI : FORCE L'AFFICHAGE EN ENTIERS ---
                            ticks: {
                                stepSize: 1, // Force le pas à 1 (pas de 0.5)
                                precision: 0, // Pas de décimales (0.00)
                                callback: function(value) {
                                    // Vérification ultime : affiche seulement si c'est un entier
                                    if (Math.floor(value) === value) {
                                        return value;
                                    }
                                }
                            },
                            grid: {
                                color: '#f0f0f0'
                            }
                        }
                    }
                }
            });

            // --- 3. ANIMATION DES CHIFFRES (Compteur) ---
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(element => {
                const text = element.textContent;
                // Détermine si c'est un montant (contient 'F') ou un nombre simple
                const isCurrency = text.includes('F');
                // Extrait le nombre brut
                const finalValue = parseInt(text.replace(/[^0-9]/g, '')) || 0;

                let startValue = 0;
                const duration = 1500;
                const startTime = performance.now();

                function updateNumber(currentTime) {
                    const elapsedTime = currentTime - startTime;
                    if (elapsedTime < duration) {
                        const progress = elapsedTime / duration;
                        const currentValue = Math.floor(progress * finalValue);

                        // Formatage pendant l'animation
                        element.textContent = isCurrency ?
                            currentValue.toLocaleString('fr-FR').replace(/\s/g, ' ') + 'F' :
                            currentValue;

                        requestAnimationFrame(updateNumber);
                    } else {
                        // Valeur finale propre
                        element.textContent = isCurrency ?
                            finalValue.toLocaleString('fr-FR').replace(/\s/g, ' ') + 'F' :
                            finalValue;
                    }
                }
                requestAnimationFrame(updateNumber);
            });

            // --- 4. ANIMATION BARRES PROGRESSION ---
            const progressBars = document.querySelectorAll('.progress');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>

@endsection
