@extends('user.layouts.template')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/styles.css') }}">

<div class="messe-container">
    <div class="messe-header">
        <h1>Mes Demandes de Messe</h1>
        <p>Retrouvez toutes vos demandes de célébration en cours.</p>
        <a href="{{ route('user.messe.create') }}" class="btn-submit">
            <span class="btn-icon">+</span>
            Nouvelle demande
        </a>
    </div>

    @if($messess->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">⛪</div>
        <h3>Aucune demande de messe</h3>
        <p>Vous n'avez pas encore fait de demande de messe.</p>
    </div>
    @else
    <div class="messe-cards">
        @foreach($messess as $messe)
        <div class="messe-card" data-status="{{ $messe->statut }}">
            <div class="card-header">
                <div class="card-badge {{ str_replace(' ', '_', $messe->statut) }}">
                    {{ ucfirst($messe->statut) }}
                </div>
                <div class="card-date">
                    {{ $messe->created_at->format('d/m/Y') }}
                </div>
            </div>
            
            <div class="card-content">
                <h3 class="card-title">
                    Messe pour 
                    @if($messe->type_intention === 'Defunt')
                        {{ $messe->nom_defunt ?? 'Défunt' }}
                    @elseif($messe->type_intention === 'Action graces')
                        Action de Grâces
                    @else
                        Intention Particulière
                    @endif
                </h3>

                 <!-- Compteur de célébrations -->
                @php
                    $celebrationCount = $messe->getCelebrationsCount();
                    $progressPercentage = $celebrationCount['total'] > 0 ? ($celebrationCount['celebrated'] / $celebrationCount['total'] * 100) : 0;
                @endphp
                <div class="celebration-counter">
                    <div class="celebration-progress">
                        <div class="progress-bar" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <div class="celebration-text">
                        Célébré {{ $celebrationCount['celebrated'] }} sur {{ $celebrationCount['total'] }} fois
                    </div>
                </div>
                
                <div class="card-details">
                    <div class="detail-item">
                        <span class="detail-label">📅 Date souhaitée:</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}</span>
                    </div>
                    
                    @if($messe->heure_souhaitee)
                    <div class="detail-item">
                        <span class="detail-label">⏰ Heure:</span>
                        <span class="detail-value">{{ $messe->heure_souhaitee }}</span>
                    </div>
                    @endif
                    
                    <div class="detail-item">
                        <span class="detail-label">⛪ Type:</span>
                        <span class="detail-value">{{ $messe->celebration_choisie ?? 'Non spécifié' }}</span>
                    </div>
                    
                    @if($messe->montant_offrande)
                    <div class="detail-item">
                        <span class="detail-label">💰 Offrande:</span>
                        <span class="detail-value">{{ number_format($messe->montant_offrande, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                </div>
                
                <div class="card-noms">
                    <strong>Noms concernés:</strong>
                    @php
                        $noms = is_array($messe->nom_prenom_concernes) 
                                ? $messe->nom_prenom_concernes 
                                : json_decode($messe->nom_prenom_concernes, true) ?? [$messe->nom_prenom_concernes];
                    @endphp
                    <div class="noms-list">
                        @foreach($noms as $nom)
                            <span class="nom-tag">{{ $nom }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="card-actions">
                <!-- CORRECTION ICI : Passage de l'ID de la messe à la route -->
                <a href="{{ route('user.messe.show', ['messe' => $messe->id]) }}" class="card-action-btn view-btn">
                    👁️ Voir détails
                </a>
                @if($messe->statut === 'en attente')
                <form action="{{ route('user.messe.destroy', ['messe' => $messe->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="card-action-btn cancel-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible.')">
                        🗑️ Supprimer
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<style>
    .messe-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .messe-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid #f35525;
    }
    
    .messe-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }
    
    .messe-card[data-status="confirmee"] {
        border-left-color: #28a745;
    }
    
    .messe-card[data-status="celebre"] {
        border-left-color: #17a2b8;
    }
    
    .messe-card[data-status="annulee"] {
        border-left-color: #6c757d;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    
    .card-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .card-badge.en_attente {
        background: #fff3cd;
        color: #856404;
    }
    
    .card-badge.confirmee {
        background: #d4edda;
        color: #155724;
    }
    
    .card-badge.celebre {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .card-badge.annulee {
        background: #f8d7da;
        color: #721c24;
    }

    .celebration-counter {
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .celebration-progress {
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 5px;
    }
    
    .progress-bar {
        height: 100%;
        background: #28a745;
        transition: width 0.3s ease;
    }
    
    .celebration-text {
        font-size: 0.9rem;
        color: #495057;
        text-align: center;
        font-weight: 500;
    }
    
    .card-date {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .card-content {
        padding: 20px;
    }
    
    .card-title {
        color: #333;
        font-size: 1.2rem;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .card-details {
        margin-bottom: 15px;
    }
    
    .detail-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding: 5px 0;
    }
    
    .detail-label {
        color: #666;
        font-weight: 500;
    }
    
    .detail-value {
        color: #333;
        font-weight: 600;
    }
    
    .card-noms {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #e9ecef;
    }
    
    .noms-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    
    .nom-tag {
        background: #f0f2f5;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        color: #495057;
    }
    
    .card-actions {
        padding: 15px 20px;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 10px;
    }
    
    .card-action-btn {
        padding: 8px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-block;
        text-align: center;
    }
    
    .view-btn {
        background: #f35525;
        color: white;
    }
    
    .view-btn:hover {
        background: #ff7c52;
        color: white;
    }
    
    .cancel-btn {
        background: #6c757d;
        color: white;
    }
    
    .cancel-btn:hover {
        background: #495057;
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .empty-state p {
        color: #666;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .messe-cards {
            grid-template-columns: 1fr;
        }
        
        .card-actions {
            flex-direction: column;
        }
        
        .card-action-btn {
            text-align: center;
        }
    }
</style>
@endsection