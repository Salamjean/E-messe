@extends('user.layouts.template')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{asset('assets/userStyle.css')}}">
<div class="modern-dashboard">
    <!-- Header avec recherche et profil -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="welcome-section">
                <h1>Bonjour, {{ Auth::user()->name }}!</h1>
                <p>Voici le résumé de vos activités</p>
            </div>
            <div class="header-actions">
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=f35525&color=fff" alt="Profile">
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques avec graphiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="card-icon">
                <div class="icon-wrapper" style="background: rgba(243, 85, 37, 0.1);">
                    <i class="fas fa-clock" style="color: #f35525;"></i>
                </div>
            </div>
            <div class="card-content">
                <h3>En attente</h3>
                <span class="stat-number">{{ $pendingMesses }}</span>
                <div class="progress-bar">
                    <div class="progress" style="width: {{ $totalMesses > 0 ? ($pendingMesses/$totalMesses)*100 : 0 }}%; background: #f35525;"></div>
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
                <h3>Confirmées</h3>
                <span class="stat-number">{{ $confirmedMesses }}</span>
                <div class="progress-bar">
                    <div class="progress" style="width: {{ $totalMesses > 0 ? ($confirmedMesses/$totalMesses)*100 : 0 }}%; background: #4CAF50;"></div>
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
                <h3>Célébrées/Annulées</h3>
                <span class="stat-number">{{ $celebratedMesses }}</span>
                <div class="progress-bar">
                    <div class="progress" style="width: {{ $totalMesses > 0 ? ($celebratedMesses/$totalMesses)*100 : 0 }}%; background: #2196F3;"></div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-icon">
                <div class="icon-wrapper" style="background: rgba(156, 39, 176, 0.1);">
                    <i class="fas fa-church" style="color: #9C27B0;"></i>
                </div>
            </div>
            <div class="card-content">
                <h3>Total</h3>
                <span class="stat-number">{{ $totalMesses }}</span>
                <div class="progress-bar">
                    <div class="progress" style="width: 100%; background: #9C27B0;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides et calendrier -->
    <div class="dashboard-content">
        <div class="content-left">
            <div class="quick-actions-card">
                <div class="card-header">
                    <h2>Actions Rapides</h2>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('user.messe.create') }}" class="action-btn">
                        <div class="btn-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span>Nouvelle demande</span>
                    </a>
                    <a href="{{ route('user.messe.index') }}" class="action-btn">
                        <div class="btn-icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <span>Mes demandes</span>
                    </a>
                    <a href="{{ route('user.messe.history') }}" class="action-btn">
                        <div class="btn-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <span>Historique</span>
                    </a>
                    <a href="#" class="action-btn">
                        <div class="btn-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <span>Exporter</span>
                    </a>
                </div>
            </div>

            <div class="recent-messes">
                <div class="card-header">
                    <h2>Prochaines Messes</h2>
                    <a href="{{ route('user.messe.index') }}" class="view-all">Voir tout</a>
                </div>
                
                @if($upcomingMesses->count() > 0)
                <div class="messe-list">
                    @foreach($upcomingMesses as $messe)
                    <div class="messe-item">
                        <div class="messe-date">
                            <span class="day">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d') }}</span>
                            <span class="month">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('M') }}</span>
                        </div>
                        <div class="messe-details">
                            <h4>{{ $messe->type_intention }}</h4>
                            <p>{{ $messe->celebration_choisie }}</p>
                            <div class="messe-meta">
                                <span class="time"><i class="fas fa-clock"></i> {{ $messe->heure_souhaitee }}</span>
                                <span class="status {{ $messe->statut }}">{{ $messe->statut }}</span>
                            </div>
                        </div>
                        <div class="messe-actions">
                            <button class="icon-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
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
                    <a href="{{ route('user.messe.create') }}" class="btn-primary">Planifier une messe</a>
                </div>
                @endif
            </div>
        </div>

        <div class="content-right">
            <div class="calendar-card">
                <div class="card-header">
                    <h2>Calendrier</h2>
                </div>
                <div class="calendar">
                    <div class="calendar-header">
                        <button class="nav-button" id="prev-month">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="current-month" id="current-month"></span>
                        <button class="nav-button" id="next-month">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="weekdays">
                        <div>Lun</div>
                        <div>Mar</div>
                        <div>Mer</div>
                        <div>Jeu</div>
                        <div>Ven</div>
                        <div>Sam</div>
                        <div>Dim</div>
                    </div>
                    <div class="days" id="calendar-days">
                        <!-- Les jours du calendrier seront générés dynamiquement en JavaScript -->
                    </div>
                </div>
            </div>

            <div class="activity-card">
                <div class="card-header">
                    <h2>Activité Récente</h2>
                </div>
                <div class="activity-list" id="recent-activities">
                    <!-- Les activités seront générées dynamiquement en JavaScript -->
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-sync-alt fa-spin" style="color: #6c757d;"></i>
                        </div>
                        <div class="activity-details">
                            <p>Chargement des activités...</p>
                            <span class="activity-time">Veuillez patienter</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des chiffres des statistiques
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(element => {
            const finalValue = parseInt(element.textContent);
            let startValue = 0;
            const duration = 1500;
            const startTime = performance.now();
            
            function updateNumber(currentTime) {
                const elapsedTime = currentTime - startTime;
                if (elapsedTime < duration) {
                    const progress = elapsedTime / duration;
                    const currentValue = Math.floor(progress * finalValue);
                    element.textContent = currentValue;
                    requestAnimationFrame(updateNumber);
                } else {
                    element.textContent = finalValue;
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
        const messesData = {!! json_encode($upcomingMesses) !!};
        
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
                    day.classList.add('event-day');
                }
                
                // Ajouter un événement de clic
                day.addEventListener('click', () => {
                    document.querySelectorAll('.day.selected').forEach(d => {
                        d.classList.remove('selected');
                    });
                    day.classList.add('selected');
                    
                    // Afficher les messes de ce jour
                    showMessesForDate(dayDate);
                });
                
                calendarDays.appendChild(day);
            }
        }
        
        function showMessesForDate(date) {
            const formattedDate = date.toDateString();
            const messesOnDate = messesData.filter(messe => {
                return new Date(messe.date_souhaitee).toDateString() === formattedDate;
            });
            
            // Mettre à jour la section d'activités récentes
            const activitiesContainer = document.getElementById('recent-activities');
            activitiesContainer.innerHTML = '';
            
            if (messesOnDate.length > 0) {
                messesOnDate.forEach(messe => {
                    const activityItem = document.createElement('div');
                    activityItem.className = 'activity-item';
                    
                    // Choisir l'icône en fonction du statut
                    let iconClass = '';
                    let iconColor = '';
                    switch(messe.statut) {
                        case 'en attente':
                            iconClass = 'fas fa-clock';
                            iconColor = '#FF9800';
                            break;
                        case 'confirmee':
                            iconClass = 'fas fa-check-circle';
                            iconColor = '#4CAF50';
                            break;
                        case 'celebre':
                            iconClass = 'fas fa-church';
                            iconColor = '#2196F3';
                            break;
                        case 'annulee':
                            iconClass = 'fas fa-times-circle';
                            iconColor = '#F44336';
                            break;
                        default:
                            iconClass = 'fas fa-info-circle';
                            iconColor = '#9C27B0';
                    }
                    
                    // Formater la date relative
                    const messeDate = new Date(messe.date_souhaitee);
                    const now = new Date();
                    const diffTime = Math.abs(now - messeDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    let timeText = '';
                    
                    if (diffDays === 0) {
                        timeText = "Aujourd'hui";
                    } else if (diffDays === 1) {
                        timeText = "Demain";
                    } else {
                        timeText = `Dans ${diffDays} jours`;
                    }
                    
                    activityItem.innerHTML = `
                        <div class="activity-icon">
                            <i class="${iconClass}" style="color: ${iconColor};"></i>
                        </div>
                        <div class="activity-details">
                            <p>${messe.type_intention} - ${messe.celebration_choisie}</p>
                            <span class="activity-time">${timeText} à ${messe.heure_souhaitee}</span>
                        </div>
                    `;
                    
                    activitiesContainer.appendChild(activityItem);
                });
            } else {
                activitiesContainer.innerHTML = `
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-calendar-day" style="color: #6c757d;"></i>
                        </div>
                        <div class="activity-details">
                            <p>Aucune messe prévue ce jour</p>
                            <span class="activity-time">${date.toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
                        </div>
                    </div>
                `;
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
        
        // Afficher les activités du jour actuel
        showMessesForDate(new Date());
    });
</script>
@endsection