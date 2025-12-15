@extends('paroisse.layouts.template')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/styles.css') }}">

<div class="messe-container">
    <div class="messe-header">
        <h1>Détails de la demande de messe</h1>
        <p>Détails complets de votre demande de célébration.</p>
        <a href="{{ route('demandes.messes.index') }}" class="btn-submit">
            ← Retour à la liste
        </a>
    </div>

    <div class="detail-card">
        <div class="detail-card-header">
            <h2>Récapitulatif de la demande</h2>
        </div>
        <div class="detail-card-body">
            @php
                // Préparation des données pour le résumé
                $noms_concernes = is_array($messe->nom_demandeur) 
                                ? $messe->nom_demandeur 
                                : json_decode($messe->nom_demandeur, true) ?? [$messe->nom_demandeur];

                $motif_display = '';
                if ($messe->type_intention === 'Defunt' && $messe->nom_defunt) {
                    $motif_display = "Pour le repos de l'âme de : " . $messe->nom_defunt;
                } elseif ($messe->type_intention === 'Action graces' && $messe->motif_action_graces) {
                    $motif_display = "En action de grâces pour : " . $messe->motif_action_graces;
                } elseif ($messe->type_intention === 'Intention particuliere' && $messe->motif_intention) {
                    $motif_display = "Pour une intention particulière : " . $messe->motif_intention;
                }
            @endphp

            {{-- Affichage des personnes concernées (Intercesseurs) --}}
            <div class="summary-section">
                <span class="detail-label">Personnes concernées (Intercesseurs) :</span>
                {{-- <div class="noms-list">
                    @forelse($noms_concernes as $nom)
                        <span class="nom-tag">{{ $nom }}</span>
                    @empty
                        <span class="nom-tag">Non spécifié</span>
                    @endforelse
                </div> --}}
            </div>

            {{-- Texte récapitulatif généré dynamiquement --}}
            <div class="summary-text-container">
                <h4>
                    
                   Messe demandé à l'intention de 
                    <strong style="color: green;">{{ $messe->motif_intention }}</strong> 
                    par l'intercession de 
                    <strong style="color: red;">{{ $messe->interception_par }}</strong>
                     par 
                    <strong style="color: blue;">{{ $messe->nom_demandeur }}</strong> 
                </h4>

                @if($motif_display)
                <p class="motif-summary">
                    <strong>Motif :</strong> {{ $motif_display }}
                </p>
                @endif
            </div>
        </div>
    </div>
    <div class="messe-details">
        <div class="detail-card">
            <div class="detail-card-header">
                <h2>Informations de la messe</h2>
                <div class="status-badge {{ str_replace(' ', '_', $messe->statut) }}">
                    {{ ucfirst($messe->statut) }}
                </div>
            </div>
            
            <div class="detail-card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Type d'intention:</span>
                        <span class="detail-value">
                            @if($messe->type_intention === 'Defunt')
                                Défunt
                            @elseif($messe->type_intention === 'Action graces')
                                Action de Grâces
                            @else
                                Intention Particulière
                            @endif
                        </span>
                    </div>
                    
                   @if($messe->type_intention === 'Defunt' && $messe->nom_defunt)
                    <div class="detail-item">
                        <span class="detail-label">Nom du défunt:</span>
                        <div class="motif-container">
                            <p class="motif-text">{{ $messe->nom_defunt }}</p>
                        </div>
                    </div>
                    @endif

                    @if($messe->type_intention === 'Action graces' && $messe->motif_action_graces)
                    <div class="detail-item">
                        <span class="detail-label">Motif action de grâces:</span>
                        <div class="motif-container">
                            <p class="motif-text">{{ $messe->motif_action_graces }}</p>
                        </div>
                    </div>
                    @endif

                    @if($messe->type_intention === 'Intention particuliere' && $messe->motif_intention)
                    <div class="detail-item">
                        <span class="detail-label">Motif intention particulière:</span>
                        <div class="motif-container">
                            <p class="motif-text">{{ $messe->motif_intention }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="detail-item">
                        <span class="detail-label">Date de début:</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}</span>
                    </div>
                    
                    @if($messe->heure_souhaitee)
                    <div class="detail-item">
                        <span class="detail-label">Heure souhaitée:</span>
                        <span class="detail-value">{{ $messe->heure_souhaitee }}</span>
                    </div>
                    @endif
                    
                    <div class="detail-item">
                        <span class="detail-label">Type de célébration:</span>
                        <span class="detail-value">{{ $messe->celebration_choisie ?? 'Non spécifié' }}</span>
                    </div>
                    
                    <!-- Afficher les dates sélectionnées -->
                    @if($messe->dates_selectionnees)
                    <div class="detail-item">
                        <span class="detail-label">Dates/Jours sélectionnés:</span>
                        <span class="detail-value">
                            @php
                                $dates = json_decode($messe->dates_selectionnees);
                            @endphp
                            @if(is_array($dates))
                                @foreach($dates as $date)
                                    <span class="date-tag">{{ $date }}</span>
                                @endforeach
                            @else
                                {{ $messe->dates_selectionnees }}
                            @endif
                        </span>
                    </div>
                    @endif
                    
                    @if($messe->paroisse)
                    <div class="detail-item">
                        <span class="detail-label">Paroisse:</span>
                        <span class="detail-value">{{ $messe->paroisse->name }}</span>
                    </div>
                    @endif
                    
                    @if($messe->montant_offrande)
                    <div class="detail-item">
                        <span class="detail-label">Montant offrande:</span>
                        <span class="detail-value">{{ number_format($messe->montant_offrande, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- <div class="detail-card">
            <div class="detail-card-header">
                <h2>Personnes concernées</h2>
            </div>
            <div class="detail-card-body">
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
        </div> --}}
        
        <div class="detail-card">
            <div class="detail-card-header">
                <h2>Informations du demandeur</h2>
            </div>
            
            <div class="detail-card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Nom et prénom:</span>
                        <span class="detail-value">{{ $messe->nom_demandeur }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $messe->email_demandeur }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Téléphone:</span>
                        <span class="detail-value">{{ $messe->telephone_demandeur }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="detail-card">
            <div class="detail-card-header">
                <h2>Informations techniques</h2>
            </div>
            
            <div class="detail-card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Date de la demande:</span>
                        <span class="detail-value">{{ $messe->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Dernière mise à jour:</span>
                        <span class="detail-value">{{ $messe->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Référence:</span>
                        <span class="detail-value">#{{ str_pad($messe->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        @if($messe->statut === 'en attente')
        <div class="action-buttons">
            <form action="#" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-cancel" onclick="return confirm('Êtes-vous sûr de vouloir Annulée cette demande ? Cette action est irréversible.')">
                    🗑️ Annulée la demande
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<style>
    .messe-details {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 30px;
    }
    
    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .detail-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    
    .detail-card-header h2 {
        color: #333;
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-badge.en_attente {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-badge.confirmee {
        background: #d4edda;
        color: #155724;
    }
    
    .status-badge.celebre {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-badge.annulee {
        background: #f8d7da;
        color: #721c24;
    }
    
    .detail-card-body {
        padding: 20px;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 15px;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        padding: 10px 0;
    }
    
    .detail-label {
        color: #666;
        font-weight: 500;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    
    .detail-value {
        color: #333;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .noms-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .nom-tag {
        background: #f0f2f5;
        padding: 10px 15px;
        border-radius: 12px;
        font-size: 0.9rem;
        color: #495057;
        margin: 5px;
        display: inline-block;
    }
    
    .date-tag {
        display: inline-block;
        background: #e8f4fd;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #0a58ca;
        margin: 2px;
    }
    
    .action-buttons {
        text-align: center;
        margin-top: 20px;
    }
    
    .btn-cancel {
        background: #dc3545;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #c82333;
    }
    
    /* Style pour les motifs de texte longs */
    .motif-container {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
        max-height: 200px;
        overflow-y: auto;
        font-family: inherit;
        line-height: 1.5;
    }
    
    .motif-text {
        color: #495057;
        font-size: 0.95rem;
        white-space: pre-wrap;
        word-wrap: break-word;
        margin: 0;
    }
    
    /* Style de la barre de défilement */
    .motif-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .motif-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .motif-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    .motif-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-card-header {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
        
        .motif-container {
            max-height: 150px;
        }
    }
</style>
@endsection