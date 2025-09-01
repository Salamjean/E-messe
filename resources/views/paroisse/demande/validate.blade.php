@extends('paroisse.layouts.template')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/styles.css') }}">

<div class="messe-container">
    <div class="messe-header">
        <h1>Toutes les demandes de messe</h1>
        <p>Retrouvez toutes vos demandes en attente de validatation.</p>
    </div>

    @if($filteredMessess->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">⛪</div>
        <h3>Aucune demande de messe</h3>
    </div>
    @else

    <div class="messe-cards">
        @foreach($filteredMessess as $messe)
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
                <a href="{{ route('paroisse.messe.show', ['messe' => $messe->id]) }}" class="card-action-btn view-btn">
                    👁️ Voir 
                </a>
                @if($messe->statut === 'en attente')
                <form action="{{ route('paroisse.messe.confirmed', ['messe' => $messe->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="card-action-btn cancel-btn" style="background-color: green; padding:10px" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible.')">
                        🗑️ Confirmer
                    </button>
                </form>
                <form action="{{ route('paroisse.messe.cancel', ['messe' => $messe->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('POST')
                    <button type="submit" class="card-action-btn cancel-btn" style="background-color: rgb(199, 12, 12); padding:10px" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ? Cette action est irréversible.')">
                        🗑️ Annulée
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Formulaire caché pour générer le PDF -->
<form id="pdfForm" action="{{ route('paroisse.messe.export-pdf') }}" method="POST" target="_blank">
    @csrf
    <input type="hidden" name="selected_ids" id="selectedIds">
</form>

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
        position: relative;
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
    
    /* Styles pour les checkboxes */
    .card-checkbox {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
    }
    
    .checkbox-label {
        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 16px;
        user-select: none;
    }
    
    .checkbox-label input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 22px;
        width: 22px;
        background-color: #fff;
        border: 2px solid #ddd;
        border-radius: 4px;
        transition: all 0.3s;
    }
    
    .checkbox-label input:checked ~ .checkmark {
        background-color: #2196F3;
        border-color: #2196F3;
    }
    
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    
    .checkbox-label input:checked ~ .checkmark:after {
        display: block;
    }
    
    .checkbox-label .checkmark:after {
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
    
    /* Styles pour les actions groupées */
    .bulk-actions {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .bulk-actions .btn {
        padding: 8px 15px;
        border-radius: 6px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .bulk-actions .btn-primary {
        background: #007bff;
        color: white;
    }
    
    .bulk-actions .btn-primary:hover {
        background: #0056b3;
    }
    
    .bulk-actions .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .bulk-actions .btn-secondary:hover {
        background: #545b62;
    }
    
    .select-all-container {
        width: 15%;
        background: white;
        padding: 12px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        
        .bulk-actions {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<script>
// Fonction pour basculer la sélection de toutes les checkboxes
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.messe-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateBulkActions();
}

// Fonction pour mettre à jour l'affichage des actions groupées
function updateBulkActions() {
    const selectedCount = document.querySelectorAll('.messe-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    
    if (selectedCount > 0) {
        bulkActions.style.display = 'flex';
    } else {
        bulkActions.style.display = 'none';
    }
}

// Fonction pour tout désélectionner
function deselectAll() {
    document.getElementById('selectAll').checked = false;
    const checkboxes = document.querySelectorAll('.messe-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = false;
    });
    updateBulkActions();
}

// Fonction pour générer le PDF
function generatePDF() {
    const selectedCheckboxes = document.querySelectorAll('.messe-checkbox:checked');
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        alert('Veuillez sélectionner au moins une demande.');
        return;
    }
    
    // Mettre à jour le champ caché avec les IDs sélectionnés
    document.getElementById('selectedIds').value = JSON.stringify(selectedIds);
    
    // Afficher un message de chargement
    const downloadBtn = document.querySelector('.bulk-actions .btn-primary');
    const originalText = downloadBtn.innerHTML;
    downloadBtn.innerHTML = '⏳ Génération en cours...';
    downloadBtn.disabled = true;
    
    // Soumettre le formulaire
    setTimeout(() => {
        document.getElementById('pdfForm').submit();
        
        // Réactiver le bouton après 3 secondes
        setTimeout(() => {
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        }, 3000);
    }, 500);
}

// Initialiser les actions groupées au chargement
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
    
    // Écouter les changements sur les checkboxes
    const checkboxes = document.querySelectorAll('.messe-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});
</script>
@endsection