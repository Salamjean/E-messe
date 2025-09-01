@extends('paroisse.layouts.template')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{asset('assets/paroiStyle.css')}}">
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

        // Calendrier dynamique
        let currentDate = new Date();
        const calendarDays = document.getElementById('calendar-days');
        const currentMonthElement = document.getElementById('current-month');
        const prevMonthButton = document.getElementById('prev-month');
        const nextMonthButton = document.getElementById('next-month');

        // Données des messes pour le calendrier (simulées pour l'exemple)
        // En réalité, vous devriez passer ces données depuis votre contrôleur
        const messesData = {!! json_encode($upcomingMessess) !!};
        
        // Formater les dates des messes pour faciliter la comparaison
        const messeDates = messesData.map(messe => {
            return new Date(messe.date_souhaitee).toDateString();
        });

        function renderCalendar() {
            // Effacer le calendrier actuel
            calendarDays.innerHTML = '';
            
            // Mettre à jour le mois en cours
            const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
                               'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            currentMonthElement.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            
            // Premier jour du mois
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            // Dernier jour du mois
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            
            // Jour de la semaine du premier jour (0 = dimanche, 1 = lundi, etc.)
            let firstDayIndex = firstDay.getDay();
            // Ajuster pour que lundi soit le premier jour (1)
            firstDayIndex = firstDayIndex === 0 ? 6 : firstDayIndex - 1;
            
            // Jour actuel
            const today = new Date();
            const todayFormatted = today.toDateString();
            
            // Remplir les cases vides avant le premier jour du mois
            for (let i = 0; i < firstDayIndex; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'day empty';
                calendarDays.appendChild(emptyDay);
            }
            
            // Remplir les jours du mois
            for (let i = 1; i <= lastDay.getDate(); i++) {
                const day = document.createElement('div');
                day.className = 'day';
                day.textContent = i;
                
                // Créer une date pour ce jour
                const dayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                const dayFormatted = dayDate.toDateString();
                
                // Vérifier si c'est aujourd'hui
                if (dayFormatted === todayFormatted) {
                    day.classList.add('today');
                }
                
                // Vérifier si ce jour a une messe
                if (messeDates.includes(dayFormatted)) {
                    day.classList.add('has-event');
                }
                
                calendarDays.appendChild(day);
            }
        }
        
        // Événements pour changer de mois
        prevMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
        
        nextMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
        
        // Initialiser le calendrier
        renderCalendar();
    });
</script>
@endsection