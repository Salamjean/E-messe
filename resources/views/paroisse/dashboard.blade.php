@extends('paroisse.layouts.template')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{asset('assets/paroiStyle.css')}}">
<link rel="stylesheet" href="{{asset('css/dashboard_paroisse.css')}}">
<!-- Ajout de SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="modern-dashboard">
    <!-- En-tête : Recherche et Bouton -->
    <div class="search-add-container">
        <!-- Barre de recherche (Style Pillule) -->
        <div class="search-wrapper">
            <input type="text" class="search-input" placeholder="Recherche">
            <i class="fas fa-search search-icon-right"></i>
        </div>

        <!-- Bouton Ajouter (Style Gold) -->
        <button class="add-btn-gold" onclick="window.location.href='{{ route('paroisse.offrande') }}'">
            <i class="fas fa-plus"></i>
            <span>Ajouter un événement</span>
        </button>
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
                        <span>+{{ $totalDemandes > 0 ? round(($pendingDemandes/$totalDemandes)*100) : 0 }}%</span>
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
                        <span>-0.03%</span> <!-- Exemple statique ou dynamique -->
                        <i class="fas fa-arrow-trend-down"></i>
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
                        <span>+{{ $totalDemandes > 0 ? round(($confirmedDemandes/$totalDemandes)*100) : 0 }}%</span>
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
                    <!-- number_format court pour l'affichage (ex: 3070F) -->
                    <span class="stat-number">{{ number_format($totalOffrandes, 0, ',', ' ') }}F</span>
                    <div class="stat-trend">
                        <span>-0.03%</span>
                        <i class="fas fa-arrow-trend-down"></i>
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
                        <span>+15.03%</span>
                        <i class="fas fa-arrow-trend-up"></i>
                    </div>
                </div>
                <!-- Lien cliquable discret pour le retrait si besoin -->
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
            <!-- Légende personnalisée -->
            <div class="chart-legend-custom">
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #f5c773"></span>
                    <span class="legend-text">Messe En attente 8.3%</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #87e0ab"></span>
                    <span class="legend-text">Messe  Célébrés 65.8%</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #6487d1"></span>
                    <span class="legend-text">Messe Confirmé 25.9%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte 2 : Évolution (Line) -->
    <div class="chart-card slide-in-right">
        <div class="chart-header line-header">
            <div class="header-text">
                <h3>Évolution mensuelle des demandes de messe</h3>
                <span class="subtitle">Totale Messes demandées</span>
            </div>
            <div class="chart-legend-top">
                <div class="legend-badge active">Cette année</div>
                <div class="legend-badge">Tannée dernière</div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="offrandes-chart"></canvas>
        </div>
    </div>
</div>

<!-- Section Actions Rapides -->
<div class="quick-actions-container">
    <h2 class="section-title">Action rapide</h2>
    
    <div class="actions-grid">
        <!-- Bouton 1 : Valider (Vert) -->
        <a href="{{ route('demandes.messes.validate') }}" class="action-card card-green">
            <div class="card-top">
                <div class="icon-circle">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <div class="card-bottom">
                <span>Valider demandes</span>
            </div>
            
            <!-- Badge de notification (Positionné en absolu) -->
            {{-- @if(isset($pendingDemandes) && $pendingDemandes > 0)
                <span class="badge">{{ $pendingDemandes }}</span>
            @endif --}}
        </a>

        <!-- Bouton 2 : Confirmées (Or/Moutarde) -->
        <a href="{{ route('demandes.messes.index') }}" class="action-card card-gold">
            <div class="card-top">
                <div class="icon-circle">
                    <i class="fas fa-tasks"></i> <!-- Icone liste -->
                </div>
            </div>
            <div class="card-bottom">
                <span>Demande confirmées</span>
            </div>
        </a>

        <!-- Bouton 3 : Ajouter Offrande (Rose/Violet) -->
        <a href="{{ route('paroisse.offrande') }}" class="action-card card-pink">
            <div class="card-top">
                <div class="icon-circle">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
            <div class="card-bottom">
                <span>Ajouter offrande</span>
            </div>
        </a>

        <!-- Bouton 4 : Historique (Bleu) -->
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
            @if(isset($upcomingMessess) && $upcomingMessess->count() > 0)
                <!-- Liste des messes (si données) -->
                <div class="messes-list-container">
                    @foreach($upcomingMessess as $messe)
                    <div class="messe-row">
                        <div class="date-box">
                            <span class="d-day">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d') }}</span>
                            <span class="d-month">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('M') }}</span>
                        </div>
                        <div class="info-box">
                            <h4>{{ $messe->type_intention }}</h4>
                            <p class="author">Par : {{ $messe->nom_demandeur }}</p>
                            <div class="meta-tags">
                                <span class="tag-time"><i class="far fa-clock"></i> {{ $messe->heure_souhaitee }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- État Vide (Comme sur l'image) -->
                <div class="empty-state">
                    <div class="calendar-icon">
                        <!-- Icône style calandre SVG ou FontAwesome -->
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
            @if(isset($latestOffrandes) && $latestOffrandes->count() > 0)
                @php 
                    // On prend juste le premier élément pour l'affichage "Grande Carte"
                    $latest = $latestOffrandes->first(); 
                @endphp
                
                <!-- La Carte Bleue Stylisée -->
                <div class="latest-request-card">
                    <div class="request-info">
                        <h3 class="requester-name">{{ $latest->nom_demandeur }}</h3>
                        <p class="request-intention">
                            <span class="label">Intention :</span> {{ $latest->type_intention }}
                        </p>
                        <p class="request-date">
                            Validé le {{ $latest->created_at->format('d F Y') }}
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

<!-- Ajout de SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        // Graphique de répartition des demandes - ADAPTÉ À LA CAPTURE
        const demandsCtx = document.getElementById('demands-chart').getContext('2d');
        let demandsChart = new Chart(demandsCtx, {
            type: 'doughnut',
            data: {
                // J'ai réorganisé l'ordre pour coller à votre image (Vert -> Bleu -> Jaune)
                // labels: ['Messe Célébrés', 'Messe Confirmé', 'En attente'],
                datasets: [{
                    data: [65.8, 25.9, 8.3], // Ordre correspondant à la taille des sections sur l'image
                    backgroundColor: [
                        '#9ce6b9', // Vert (Le grand)
                        '#6487d1', // Bleu (Le moyen)
                        '#f5c773'  // Jaune (Le petit)
                    ],
                    borderWidth: 0,   // Pas de bordure classique
                    borderRadius: 20, // C'est ici qu'on arrondit les bouts des sections !
                    spacing: 10,      // C'est ici qu'on crée l'espace vide entre les sections !
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    animateScale: true, // Ajoute une animation de "zoom" au chargement
                    animateRotate: true // Ajoute une animation de rotation au chargement
                },
                plugins: {
                    legend: {
                        display: false 
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                },
                cutout: '70%', // Épaisseur de l'anneau (plus le % est haut, plus l'anneau est fin)
            }
        });

        // Graphique d'évolution des demandes de messe - ADAPTÉ À LA CAPTURE
        const offrandesCtx = document.getElementById('offrandes-chart').getContext('2d');
        new Chart(offrandesCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juu'],
                datasets: [{
                    label: 'Cette année',
                    data: [120, 380, 150, 300, 440, 300, 290],
                    borderColor: '#828282',
                    backgroundColor: '#f2f2f2',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#828282',
                    pointRadius: 1,
                    pointHoverRadius: 1
                }, {
                    label: 'Année dernière',
                    data: [350, 220, 280, 280, 320, 190, 350],
                    borderColor: '#abc4eb',
                    backgroundColor: '#fafafa',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#abc4eb',
                    pointRadius: 1,
                    pointHoverRadius: 1,
                    borderDash: [5, 5]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // On utilise notre légende personnalisée en haut
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 300,
                        ticks: {
                            stepSize: 100,
                            callback: function(value) {
                                return value;
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
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

        // Gestion du popup SweetAlert2 pour le retrait - CODE CORRIGÉ
        const retraitBtn = document.querySelector('.btn-retrait');
        if (retraitBtn) {
            retraitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Créer le formulaire HTML pour SweetAlert2
                const formHTML = `
                    <form id="swalRetraitForm">
                        <div class="mb-3">
                            <label class="form-label">Solde disponible</label>
                            <input type="text" class="form-control" value="{{ number_format($soldeDisponible, 0, ',', ' ') }} FCFA" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Montant à retirer (FCFA)</label>
                            <input type="number" name="montant" id="swalMontant" class="form-control" required min="1000" max="{{ $soldeDisponible }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Méthode de retrait</label>
                            <select name="methode" id="swalMethode" class="form-select" required>
                                <option value="">Sélectionnez une méthode</option>
                                <option value="wave">Wave</option>
                                <option value="orange_money">Orange Money</option>
                                <option value="mtn_money">MTN Money</option>
                                <option value="virement_bancaire">Virement Bancaire</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Numéro de compte / Téléphone</label>
                            <input type="text" name="numero_compte" id="swalNumeroCompte" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nom du titulaire du compte</label>
                            <input type="text" name="nom_titulaire" id="swalNomTitulaire" class="form-control" required>
                        </div>
                    </form>
                `;
                
                // Afficher le popup SweetAlert2 avec le formulaire
                Swal.fire({
                    title: 'Demander un retrait',
                    html: formHTML,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#FFC107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Demander le retrait',
                    cancelButtonText: 'Annuler',
                    focusConfirm: false,
                    preConfirm: () => {
                        const montant = parseFloat(document.getElementById('swalMontant').value);
                        const methode = document.getElementById('swalMethode').value;
                        const numeroCompte = document.getElementById('swalNumeroCompte').value;
                        const nomTitulaire = document.getElementById('swalNomTitulaire').value;
                        
                        // Validation
                        if (!montant || montant < 1000 || montant > {{ $soldeDisponible }}) {
                            Swal.showValidationMessage('Montant invalide. Le montant doit être entre 1000 et {{ $soldeDisponible }} FCFA');
                            return false;
                        }
                        
                        if (!methode) {
                            Swal.showValidationMessage('Veuillez sélectionner une méthode de retrait');
                            return false;
                        }
                        
                        if (!numeroCompte) {
                            Swal.showValidationMessage('Veuillez saisir un numéro de compte/téléphone');
                            return false;
                        }
                        
                        if (!nomTitulaire) {
                            Swal.showValidationMessage('Veuillez saisir le nom du titulaire du compte');
                            return false;
                        }
                        
                        return {
                            montant: montant,
                            methode: methode,
                            numero_compte: numeroCompte,
                            nom_titulaire: nomTitulaire
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Afficher un indicateur de chargement
                        Swal.fire({
                            title: 'Traitement en cours...',
                            text: 'Veuillez patienter',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Préparer les données pour l'envoi
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('montant', result.value.montant);
                        formData.append('methode', result.value.methode);
                        formData.append('numero_compte', result.value.numero_compte);
                        formData.append('nom_titulaire', result.value.nom_titulaire);
                        
                        // Envoyer la requête AJAX
                        fetch('{{ route("paroisse.retrait.request") }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            // Fermer l'indicateur de chargement
                            Swal.close();
                            
                            if (!response.ok) {
                                throw new Error('Erreur réseau');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Succès!',
                                    text: data.message || 'Votre demande de retrait a été envoyée avec succès.',
                                    icon: 'success',
                                    confirmButtonColor: '#FFC107'
                                }).then(() => {
                                    // Recharger la page pour mettre à jour les données
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Erreur!',
                                    text: data.message || 'Une erreur s\'est produite lors de la demande de retrait.',
                                    icon: 'error',
                                    confirmButtonColor: '#FFC107'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Erreur!',
                                text: 'Une erreur s\'est produite lors de la communication avec le serveur.',
                                icon: 'error',
                                confirmButtonColor: '#FFC107'
                            });
                            console.error('Erreur:', error);
                        });
                    }
                });
            });
        }
    });
</script>

@endsection