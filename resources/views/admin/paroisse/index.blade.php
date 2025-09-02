@extends('admin.layouts.template')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="paroisses-container">
    <div class="header-section">
        <h1><i class="fas fa-church"></i> Gestion des Paroisses</h1>
        <p>Consultez et gérez toutes les paroisses enregistrées dans le système</p>
    </div>

    <div class="action-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Rechercher une paroisse...">
        </div>
        <button class="btn btn-primary" id="add-paroisse-btn">
            <i class="fas fa-plus"></i> Nouvelle Paroisse
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
            <button class="btn-close"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
            <button class="btn-close"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <div class="filters">
        <div class="filter-group">
            <label for="localisation-filter">Filtrer par localisation:</label>
            <select id="localisation-filter">
                <option value="">Toutes les localisations</option>
                @foreach($localisations as $localisation)
                    <option value="{{ $localisation }}">{{ $localisation }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label for="sort-by">Trier par:</label>
            <select id="sort-by">
                <option value="name">Nom (A-Z)</option>
                <option value="name_desc">Nom (Z-A)</option>
                <option value="recent">Plus récent</option>
                <option value="oldest">Plus ancien</option>
            </select>
        </div>
    </div>

    @if($paroisses->count() > 0)
        <div class="paroisses-grid" id="paroisses-grid">
            @foreach($paroisses as $paroisse)
                <div class="paroisse-card" data-localisation="{{ $paroisse->localisation }}" data-name="{{ strtolower($paroisse->name) }}">
                    <div class="card-header">
                        <div class="profile-img">
                            <img src="{{ $paroisse->profile_picture ? asset('storage/' . $paroisse->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($paroisse->name) . '&size=200&background=f35525&color=fff' }}" 
                                 alt="{{ $paroisse->name }}">
                        </div>
                        <div class="actions">
                            <button class="btn-icon edit-btn" data-id="{{ $paroisse->id }}" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete-btn" data-id="{{ $paroisse->id }}" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="paroisse-name">{{ $paroisse->name }}</h3>
                        <div class="paroisse-info">
                            <p><i class="fas fa-map-marker-alt"></i> {{ $paroisse->localisation }}</p>
                            <p><i class="fas fa-phone"></i> {{ $paroisse->contact }}</p>
                            <p><i class="fas fa-envelope"></i> {{ $paroisse->email }}</p>
                            <p><i class="fas fa-money-bill-wave"></i> 
                                {{ $paroisse->montant_offrande ? number_format($paroisse->montant_offrande, 0, ',', ' ') . ' FCFA' : 'Non défini' }}
                            </p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="badge">Créé le {{ $paroisse->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-church"></i>
            <h3>Aucune paroisse enregistrée</h3>
            <p>Commencez par ajouter une nouvelle paroisse</p>
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une paroisse
            </button>
        </div>
    @endif
</div>

<style>
    :root {
        --primary: #f35525;
        --dark: #181824;
        --light: #ffffff;
        --gray: #f8f9fa;
        --gray-dark: #eaeaea;
        --success: #28a745;
        --danger: #dc3545;
    }
    
    .paroisses-container {
        padding: 20px;
        background-color: #f5f7fb;
        min-height: 100vh;
    }
    
    .header-section {
        margin-bottom: 30px;
    }
    
    .header-section h1 {
        color: var(--dark);
        font-weight: 700;
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .header-section h1 i {
        color: var(--primary);
        margin-right: 15px;
        font-size: 32px;
    }
    
    .header-section p {
        color: #6c757d;
        font-size: 16px;
        margin: 0;
    }
    
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .search-box {
        position: relative;
        flex: 1;
        max-width: 400px;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .search-box input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid var(--gray-dark);
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    
    .search-box input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(243, 85, 37, 0.2);
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    
    .btn-primary:hover {
        background: #e04a1f;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(243, 85, 37, 0.3);
    }
    
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: rgba(243, 85, 37, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .stat-icon i {
        font-size: 24px;
        color: var(--primary);
    }
    
    .stat-info h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }
    
    .stat-info p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 14px;
    }
    
    .filters {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .filter-group label {
        font-weight: 500;
        color: var(--dark);
        font-size: 14px;
    }
    
    .filter-group select {
        padding: 10px 15px;
        border: 2px solid var(--gray-dark);
        border-radius: 8px;
        background: white;
        font-size: 14px;
    }
    
    .paroisses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .paroisse-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .paroisse-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        position: relative;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .profile-img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--gray-dark);
    }
    
    .profile-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .edit-btn {
        background: rgba(40, 167, 69, 0.1);
        color: var(--success);
    }
    
    .edit-btn:hover {
        background: var(--success);
        color: white;
    }
    
    .delete-btn {
        background: rgba(220, 53, 69, 0.1);
        color: var(--danger);
    }
    
    .delete-btn:hover {
        background: var(--danger);
        color: white;
    }
    
    .card-body {
        padding: 0 20px 20px;
    }
    
    .paroisse-name {
        margin: 0 0 15px;
        color: var(--dark);
        font-size: 18px;
        font-weight: 600;
    }
    
    .paroisse-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .paroisse-info p {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6c757d;
        font-size: 14px;
    }
    
    .paroisse-info i {
        width: 16px;
        color: var(--primary);
    }
    
    .card-footer {
        padding: 15px 20px;
        background: var(--gray);
        border-top: 1px solid var(--gray-dark);
    }
    
    .badge {
        background: rgba(243, 85, 37, 0.1);
        color: var(--primary);
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .empty-state i {
        font-size: 60px;
        color: var(--primary);
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        color: var(--dark);
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #6c757d;
        margin-bottom: 25px;
    }
    
    .alert {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .alert-success {
        background: #e6f7ee;
        color: #0a7b4c;
        border: 1px solid #a3e6c7;
    }
    
    .alert-danger {
        background: #fde8e8;
        color: #e53e3e;
        border: 1px solid #f8b6b6;
    }
    
    .alert i {
        margin-right: 10px;
        font-size: 20px;
    }
    
    .btn-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: inherit;
    }
    
    @media (max-width: 768px) {
        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .filters {
            flex-direction: column;
        }
        
        .paroisses-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonctionnalité de recherche
        const searchInput = document.getElementById('search-input');
        const paroissesGrid = document.getElementById('paroisses-grid');
        const paroisseCards = document.querySelectorAll('.paroisse-card');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            paroisseCards.forEach(card => {
                const paroisseName = card.getAttribute('data-name');
                if (paroisseName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Filtrage par localisation
        const localisationFilter = document.getElementById('localisation-filter');
        
        localisationFilter.addEventListener('change', function() {
            const selectedLocalisation = this.value;
            
            paroisseCards.forEach(card => {
                const cardLocalisation = card.getAttribute('data-localisation');
                if (selectedLocalisation === '' || cardLocalisation === selectedLocalisation) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Tri des cartes
        const sortBy = document.getElementById('sort-by');
        
        sortBy.addEventListener('change', function() {
            // Cette fonctionnalité nécessiterait une actualisation de la page
            // ou une requête AJAX pour trier côté serveur
            // Pour l'instant, on redirige avec le paramètre de tri
            const sortValue = this.value;
            window.location.href = "{{ route('paroisse.index') }}?sort=" + sortValue;
        });
        
        // Fermeture des alertes
        document.querySelectorAll('.btn-close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        
        // Boutons d'action (modifier/supprimer)
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const paroisseId = this.getAttribute('data-id');
                window.location.href = "/admin/parish/" + paroisseId + "/edit";
            });
        });
        
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const paroisseId = this.getAttribute('data-id');
                if (confirm('Êtes-vous sûr de vouloir supprimer cette paroisse ?')) {
                    // Soumettre le formulaire de suppression
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "/admin/parish/" + paroisseId;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = "{{ csrf_token() }}";
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    
                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
        
        // Bouton d'ajout
        document.getElementById('add-paroisse-btn').addEventListener('click', function() {
            window.location.href = "{{ route('paroisse.create') }}";
        });
    });
</script>
@endsection