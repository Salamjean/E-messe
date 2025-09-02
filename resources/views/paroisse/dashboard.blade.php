@extends('paroisse.layouts.template')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{asset('assets/paroiStyle.css')}}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="modern-dashboard">
    <!-- En-tête du tableau de bord -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="welcome-section">
                <h1>Tableau de Bord Paroissial</h1>
                <p>Bienvenue, {{ Auth::guard('paroisse')->user()->nom }}!</p>
            </div>
            <div class="header-actions">
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::guard('paroisse')->user()->name) }}&background=f35525&color=fff" alt="Profile">
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="card-icon">
                <div class="icon-wrapper" style="background: rgba(243, 85, 37, 0.1);">
                    <i class="fas fa-clock" style="color: #f35525;"></i>
                </div>
            </div>
            <div class="card-content">
                <h3>Demandes en attente</h3>
                <span class="stat-number">{{ $pendingDemandes }}</span>
                <div class="progress-bar">
                    <div class="progress" style="width: {{ $totalDemandes > 0 ? ($pendingDemandes/$totalDemandes)*100 : 0 }}%; background: #f35525;"></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-icon">
                <div class="icon-wrapper" style="background: rgba(76, 175, 80, 0.1);">
                    <i class="fas fa-check-circle" style="color: #4CAF50;"></i>
                </div>
            </div>
            <div class="card-content">
                <h3>Demandes confirmées</h3>
                <span class="stat-number">{{ $confirmedDemandes }}</span>
                <div class="progress-bar">
                    <div class="progress" style="width: {{ $totalDemandes > 0 ? ($confirmedDemandes/$totalDemandes)*100 : 0 }}%; background: #4CAF50;"></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-icon">
                <div class="icon-wrapper" style="background: rgba(33, 150, 243, 0.1);">
                    <i class="fas fa-history" style="color: #2196F3;"></i>
                </div>
            </div>
            <div class="card-content">
                <h3>Messes célébrées</h3>
                <span class="stat-number">{{ $celebratedDemandes }}</span>
                <div class="progress-bar">
                    <div class="progress" style="width: {{ $totalDemandes > 0 ? ($celebratedDemandes/$totalDemandes)*100 : 0 }}%; background: #2196F3;"></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-icon">
                <div class="icon-wrapper" style="background: rgba(156, 39, 176, 0.1);">
                    <i class="fas fa-coins" style="color: #9C27B0;"></i>
                </div>
            </div>
            <div class="card-content">
                <h3>Montant de l'offrande</h3>
                <span class="stat-number">{{ number_format($totalOffrandes, 0, ',', ' ') }} FCFA</span>
                <div class="progress-bar">
                    <div class="progress" style="width: 100%; background: #9C27B0;"></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
        <div class="card-icon">
            <div class="icon-wrapper" style="background: rgba(255, 193, 7, 0.1);">
                <i class="fas fa-wallet" style="color: #FFC107;"></i>
            </div>
        </div>
        <div class="card-content">
            <h3>Portefeuille électronique</h3>
            <span class="stat-number">{{ number_format($soldeDisponible, 0, ',', ' ') }} FCFA</span>
            <div class="progress-bar">
                <div class="progress" style="width: 100%; background: #FFC107;"></div>
            </div>
            <div class="mt-2">
                <a href="#" class="btn-retrait" style="color: #FFC107; font-size: 12px;">
                    <i class="fas fa-money-bill-wave"></i> Demander un retrait
                </a>
            </div>
        </div>
    </div>
</div>

    <!-- Graphiques -->
    <div class="charts-section">
        <div class="chart-card">
            <div class="chart-header">
                <h3>Répartition des demandes</h3>
                <select id="chart-type-selector" class="chart-selector">
                    <option value="doughnut">Anneau</option>
                    <option value="pie">Circulaire</option>
                    <option value="bar">Barres</option>
                </select>
            </div>
            <div class="chart-container">
                <canvas id="demands-chart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <h3>Évolution mensuelle des offrandes</h3>
            </div>
            <div class="chart-container">
                <canvas id="offrandes-chart"></canvas>
            </div>
        </div>

        
    </div>

    <!-- Actions rapides -->
    <div class="quick-actions">
        <h2>Actions Rapides</h2>
        <div class="action-buttons">
            <a href="{{ route('demandes.messes.validate') }}" class="action-btn">
                <div class="btn-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span>Valider demandes</span>
                @if($pendingDemandes > 0)
                <span class="badge">{{ $pendingDemandes }}</span>
                @endif
            </a>
            <a href="{{ route('demandes.messes.index') }}" class="action-btn">
                <div class="btn-icon">
                    <i class="fas fa-list"></i>
                </div>
                <span>Demandes confirmées</span>
            </a>
            <a href="{{ route('paroisse.offrande') }}" class="action-btn">
                <div class="btn-icon">
                    <i class="fas fa-plus"></i>
                </div>
                <span>Ajouter offrande</span>
            </a>
            <a href="{{ route('demandes.messes.history') }}" class="action-btn">
                <div class="btn-icon">
                    <i class="fas fa-history"></i>
                </div>
                <span>Historique</span>
            </a>
        </div>
    </div>

    <!-- Section des prochaines messes -->
    <div class="dashboard-content">
        <div class="content-left">
            <div class="upcoming-section">
                <div class="section-header">
                    <h2>Prochaines Messes à Célébrer</h2>
                    <a href="{{ route('demandes.messes.index') }}" class="view-all">Voir tout</a>
                </div>
                
                @if($upcomingMessess->count() > 0)
                <div class="messe-list">
                    @foreach($upcomingMessess as $messe)
                    <div class="messe-item">
                        <div class="messe-date">
                            <span class="day">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d') }}</span>
                            <span class="month">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('M') }}</span>
                        </div>
                        <div class="messe-details">
                            <h4>{{ $messe->type_intention }}</h4>
                            <p>Demandé par: {{ $messe->nom_demandeur }}</p>
                            <div class="messe-meta">
                                <span class="time"><i class="fas fa-clock"></i> {{ $messe->heure_souhaitee }}</span>
                                <span class="status {{ $messe->statut }}">{{ $messe->statut }}</span>
                            </div>
                        </div>
                        <div class="messe-actions">
                            <a href="#" class="icon-btn" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <p>Aucune messe prévue</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Dernières offrandes -->
        <div class="recent-offrandes">
            <div class="card-header">
                <h2>Dernières Offrandes</h2>
                <a href="#" class="view-all">Voir tout</a>
            </div>
            <div class="offrande-list">
                @if($latestOffrandes->count() > 0)
                    @foreach($latestOffrandes as $offrande)
                    <div class="offrande-item">
                        <div class="offrande-icon">
                            <i class="fas fa-donate" style="color: #4CAF50;"></i>
                        </div>
                        <div class="offrande-details">
                            <p>Offrande pour {{ $offrande->type_intention }}</p>
                            <span class="offrande-amount">{{ number_format($offrande->montant_offrande, 0, ',', ' ') }} FCFA</span>
                            <span class="offrande-donor">Par: {{ $offrande->nom_demandeur }}</span>
                            <span class="offrande-time">{{ $offrande->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="offrande-item">
                        <div class="offrande-icon">
                            <i class="fas fa-donate" style="color: #6c757d;"></i>
                        </div>
                        <div class="offrande-details">
                            <p>Aucune offrande enregistrée</p>
                            <span class="offrande-time">Les offrandes apparaîtront ici</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal pour effectuer un retrait -->
<!-- Modal pour effectuer un retrait -->
<div class="modal fade" id="retraitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Demander un retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="retraitForm" action="{{ route('paroisse.retrait.request') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Solde disponible</label>
                        <input type="text" class="form-control" value="{{ number_format($soldeDisponible, 0, ',', ' ') }} FCFA" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Montant à retirer (FCFA)</label>
                        <input type="number" name="montant" class="form-control" required min="1000" max="{{ $soldeDisponible }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Méthode de retrait</label>
                        <select name="methode" class="form-select" required>
                            <option value="">Sélectionnez une méthode</option>
                            <option value="wave">Wave</option>
                            <option value="orange_money">Orange Money</option>
                            <option value="mtn_money">MTN Money</option>
                            <option value="virement_bancaire">Virement Bancaire</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Numéro de compte / Téléphone</label>
                        <input type="text" name="numero_compte" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nom du titulaire du compte</label>
                        <input type="text" name="nom_titulaire" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Demander le retrait</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap CSS -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des chiffres des statistiques
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(element => {
            const finalValue = parseInt(element.textContent.replace(/\s/g, '')) || 0;
            let startValue = 0;
            const duration = 1500;
            const startTime = performance.now();
            
            function updateNumber(currentTime) {
                const elapsedTime = currentTime - startTime;
                if (elapsedTime < duration) {
                    const progress = elapsedTime / duration;
                    const currentValue = Math.floor(progress * finalValue);
                    element.textContent = element.textContent.includes('FCFA') 
                        ? currentValue.toLocaleString('fr-FR') + ' FCFA' 
                        : currentValue;
                    requestAnimationFrame(updateNumber);
                } else {
                    element.textContent = element.textContent.includes('FCFA') 
                        ? finalValue.toLocaleString('fr-FR') + ' FCFA' 
                        : finalValue;
                }
            }
            
            requestAnimationFrame(updateNumber);
        });

        // Animation des barres de progression
        const progressBars = document.querySelectorAll('.progress');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 300);
        });

        // Graphique de répartition des demandes
        const demandsCtx = document.getElementById('demands-chart').getContext('2d');
        let demandsChart = new Chart(demandsCtx, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'Confirmées', 'Célébrées'],
                datasets: [{
                    data: [{{ $pendingDemandes }}, {{ $confirmedDemandes }}, {{ $celebratedDemandes }}],
                    backgroundColor: [
                        '#f35525',
                        '#4CAF50',
                        '#2196F3'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });

        // Changer le type de graphique
        document.getElementById('chart-type-selector').addEventListener('change', function() {
            demandsChart.destroy();
            demandsChart = new Chart(demandsCtx, {
                type: this.value,
                data: {
                    labels: ['En attente', 'Confirmées', 'Célébrées'],
                    datasets: [{
                        data: [{{ $pendingDemandes }}, {{ $confirmedDemandes }}, {{ $celebratedDemandes }}],
                        backgroundColor: [
                            '#f35525',
                            '#4CAF50',
                            '#2196F3'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });

        // Graphique d'évolution des offrandes (données réelles)
        const offrandesCtx = document.getElementById('offrandes-chart').getContext('2d');
        const monthlyOffrandeData = @json($monthlyOffrandeData);
        const monthlyOffrandeLabels = @json($monthlyOffrandeLabels);

        new Chart(offrandesCtx, {
            type: 'line',
            data: {
                labels: monthlyOffrandeLabels,
                datasets: [{
                    label: 'Montant des offrandes (FCFA)',
                    data: monthlyOffrandeData,
                    borderColor: '#9C27B0',
                    backgroundColor: 'rgba(156, 39, 176, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#9C27B0',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw.toLocaleString('fr-FR')} FCFA`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' FCFA';
                            }
                        }
                    }
                }
            }
        });

        // Gestion du modal de retrait - CODE CORRIGÉ
        const retraitBtn = document.querySelector('.btn-retrait');
        if (retraitBtn) {
            retraitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Bouton retrait cliqué');
                
                // Vérifier si Bootstrap est chargé
                if (typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined') {
                    const retraitModalElement = document.getElementById('retraitModal');
                    if (retraitModalElement) {
                        const retraitModal = new bootstrap.Modal(retraitModalElement);
                        retraitModal.show();
                    } else {
                        console.error('Element #retraitModal non trouvé');
                    }
                } else {
                    console.error('Bootstrap non chargé');
                    // Fallback manuel si Bootstrap n'est pas disponible
                    const modal = document.getElementById('retraitModal');
                    if (modal) {
                        modal.style.display = 'block';
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');
                        
                        // Ajouter le backdrop manuellement
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                }
            });
        } else {
            console.error('Bouton .btn-retrait non trouvé');
        }

        // Fermer le modal (fallback manuel)
        const closeButtons = document.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                    document.body.classList.remove('modal-open');
                    
                    // Supprimer le backdrop
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
            });
        });

       // Valider le formulaire de retrait
        const retraitForm = document.getElementById('retraitForm');
        if (retraitForm) {
            retraitForm.addEventListener('submit', function(e) {
                const montantInput = this.querySelector('input[name="montant"]');
                if (montantInput) {
                    const montant = parseFloat(montantInput.value);
                    const solde = parseFloat({{ $soldeDisponible }});
                    
                    if (montant > solde) {
                        e.preventDefault();
                        alert('Le montant demandé dépasse votre solde disponible.');
                        return false;
                    }
                    
                    if (montant < 1000) {
                        e.preventDefault();
                        alert('Le montant minimum de retrait est de 1 000 FCFA.');
                        return false;
                    }
                }
            });
        }
    });
</script>

@endsection