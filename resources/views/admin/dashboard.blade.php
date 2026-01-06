@extends('admin.layouts.template')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('dashboard/admin.css') }}">

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord Administrateur</h1>
            <p>Vue d'ensemble de l'activité de la plateforme</p>
        </div>

        <!-- Cartes de statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $usersCount }}</h3>
                    <p>Utilisateurs inscrits</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon paroisses">
                    <i class="fas fa-church"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $paroissesCount }}</h3>
                    <p>Paroisses enregistrées</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon offrandes">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($totalOffrandes, 0, ',', ' ') }} FCFA</h3>
                    <p>Total montant de demandes de messe</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon evenements">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-info">
                    <div class="status-header">
                        <div class="status-indicator">En ligne</div>
                    </div>
                    <h3>{{ $connectedUsersCount }}</h3>
                    <p>Utilisateurs connectés</p>
                </div>
            </div>
        </div>



        <!-- Actions rapides -->
        <div class="quick-actions">
            <div class="action-card" onclick="window.location.href='#'">
                <div class="action-icon user">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Gérer les utilisateurs</h3>
                <p>Supprimer des utilisateurs</p>
            </div>

            <div class="action-card" onclick="window.location.href='{{ route('paroisse.index') }}'">
                <div class="action-icon paroisse">
                    <i class="fas fa-church"></i>
                </div>
                <h3>Gérer les paroisses</h3>
                <p>Enregistrer, modifier et supprimer une paroisse</p>
            </div>

            <div class="action-card" onclick="window.location.href='#'">
                <div class="action-icon offrande">
                    <i class="fas fa-donate"></i>
                </div>
                <h3>Voir les montants des demandes de messe</h3>
                <p>Consulter les paroisses et leur montant de demande de messe</p>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Activité des montants des demandes (30 derniers jours)</h3>
                </div>
                <div class="chart-container">
                    <canvas id="offrandesChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Répartition des paroisses</h3>
                </div>
                <div class="chart-container">
                    <canvas id="paroissesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Activité récente -->
        <div class="recent-section">
            <div class="recent-card">
                <div class="recent-header">
                    <h3><i class="fas fa-users"></i> Utilisateurs récents</h3>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="recent-list">
                    @foreach ($recentUsers as $user)
                        <div class="recent-item">
                            <img src="{{ $user->profile_picture
                                ? (Str::startsWith($user->profile_picture, ['http://', 'https://'])
                                    ? $user->profile_picture
                                    : asset('storage/' . $user->profile_picture))
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=f35525&color=fff' }}"
                                alt="{{ $user->name }}">

                            <div class="recent-item-info">
                                <h4>{{ $user->name }}</h4>
                                <p>{{ $user->email }}</p>
                            </div>

                            <div class="recent-item-date">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="recent-card">
                <div class="recent-header">
                    <h3><i class="fas fa-church"></i> Paroisses récentes</h3>
                    <a href="{{ route('paroisse.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="recent-list">
                    @foreach ($recentParoisses as $paroisse)
                        <div class="recent-item">
                            <img src="{{ $paroisse->profile_picture
                                ? (Str::startsWith($paroisse->profile_picture, ['http://', 'https://'])
                                    ? $paroisse->profile_picture
                                    : asset('storage/' . $paroisse->profile_picture))
                                : 'https://ui-avatars.com/api/?name=' . urlencode($paroisse->name) . '&size=200&background=f35525&color=fff' }}"
                                alt="{{ $paroisse->name }}">

                            <div class="recent-item-info">
                                <h4>{{ $paroisse->name }}</h4>
                                <p>
                                    @if ($paroisse->commune && $paroisse->commune->ville)
                                        {{ $paroisse->commune->nom_commune }},
                                        {{ $paroisse->commune->ville->nom_ville }}
                                    @else
                                        Localisation non définie
                                    @endif
                                </p>
                            </div>

                            <div class="recent-item-date">
                                {{ $paroisse->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des offrandes
            const offrandesCtx = document.getElementById('offrandesChart').getContext('2d');
            const offrandesChart = new Chart(offrandesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($offrandesLabels) !!},
                    datasets: [{
                        label: 'Montant des demandes de messe (FCFA)',
                        data: {!! json_encode($offrandesData) !!},
                        borderColor: '#f35525',
                        backgroundColor: 'rgba(243, 85, 37, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Graphique des paroisses - CORRECTION ICI
            const paroissesCtx = document.getElementById('paroissesChart').getContext('2d');
            const paroissesChart = new Chart(paroissesCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($paroissesStatsLabels) !!},
                    datasets: [{
                        data: {!! json_encode($paroissesStatsData) !!},
                        backgroundColor: [
                            '#f35525',
                            '#28a745',
                            '#17a2b8',
                            '#ffc107',
                            '#6f42c1',
                            '#e83e8c',
                            '#20c997',
                            '#fd7e14',
                            '#6610f2',
                            '#6c757d'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
