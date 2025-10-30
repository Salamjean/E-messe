@extends('user.layouts.template')

@section('content')
<link rel="stylesheet" href="{{asset('assets/styles.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="messe-container">
    <div class="messe-header">
        <h1>Demande de Messe</h1>
        <p>Remplissez ce formulaire pour demander une célébration de messe selon vos intentions.</p>
    </div>

    <form action="{{ route('user.messe.store') }}" method="POST" class="messe-form" id="messeForm">
        @csrf
        
        <!-- Section: Intention de la messe -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">🙏</div>
                <h2>Intention de messe</h2>
            </div>
            
            <div class="form-group">
                <label for="motif_intention">Motif de la messe *</label>
                <textarea id="motif_intention" name="motif_intention" rows="4" placeholder="Précisez l'intention de votre messe (défunt, action de grâces, intention particulière, etc.)" required>{{ old('motif_intention') }}</textarea>
                @error('motif_intention')
                    <div class="error-message" style="color: rgb(184, 8, 8)">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="interception_par">Interception par</label>
                <input type="text" id="interception_par" name="interception_par" value="{{ old('interception_par') }}" placeholder="Nom de la personne qui intercédera (optionnel)">
                @error('interception_par')
                    <div class="error-message" style="color: rgb(184, 8, 8)">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        
        <!-- Section: Détails de la messe -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">⛪</div>
                <h2>Détails de la messe</h2>
            </div>
            
            <div class="form-row1">
                <div class="form-group">
                    <label for="ville_id">Ville *</label>
                    <select id="ville_id" name="ville_id" required>
                        <option value="">Sélectionnez une ville</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville->id }}">{{ $ville->nom_ville }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="commune_id">Commune *</label>
                    <select id="commune_id" name="commune_id" required disabled>
                        <option value="">Sélectionnez d'abord une ville</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="paroisse_id">Paroisse *</label>
                    <select id="paroisse_id" name="paroisse_id" required disabled>
                        <option value="">Sélectionnez d'abord une commune</option>
                    </select>
                    @error('paroisse_id')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                 <div class="form-group">
                    <label for="celebration_choisie">Type de célébration *</label>
                    <select id="celebration_choisie" name="celebration_choisie" required>
                        <option value="">Sélectionnez une option</option>
                        <option value="Messe quotidienne">Messe quotidienne</option>
                        <option value="Messe dominicale">Messe dominicale</option>
                        <option value="Messe solennelle">Messe solennelle</option>
                    </select>
                    @error('celebration_choisie')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Champs conditionnels pour les jours de messe -->
            <div id="jours_messe_quotidienne" class="conditional-field">
                <div class="form-group">
                    <label>Jours de la semaine *</label>
                    <div class="jours-selection">
                        @php
                            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        @endphp
                        @foreach($jours as $index => $jour)
                            <label class="jour-checkbox">
                                <input type="checkbox" name="jours_quotidienne[]" value="{{ $index + 1 }}">
                                <span class="checkmark"></span>
                                {{ $jour }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="jours_messe_dominicale" class="conditional-field">
                <div class="form-group">
                    <label>Dimanches du mois *</label>
                    <div class="jours-selection" id="dimanches-container">
                        <!-- Les dimanches seront générés dynamiquement en JS -->
                    </div>
                </div>
            </div>

            <!-- Ligne avec Montant, Date et Heure -->
            <div class="form-row">
                <div class="form-group">
                    <label for="montant_offrande">Montant des demandes de messes (FCFA)</label>
                    <div class="input-with-icon">
                        <input type="number" id="montant_offrande" name="montant_offrande" step="0.01" min="0" readonly>
                    </div>
                    <small id="montant-details">Ce montant est suggéré par la paroisse sélectionnée</small>
                </div>
                
                <div class="form-group">
                    <label for="date_souhaitee">Date de début *</label>
                    <input type="date" id="date_souhaitee" value="{{old('date_souhaitee')}}" name="date_souhaitee" required>
                    @error('date_souhaitee')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="heure_souhaitee">Heure souhaitée</label>
                    <input type="time" id="heure_souhaitee" value="{{old('heure_souhaitee')}}" name="heure_souhaitee">
                    @error('heure_souhaitee')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Section: Informations du demandeur -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">👤</div>
                <h2>Informations du demandeur</h2>
            </div>
            
            <!-- Ligne avec Nom, Email et Téléphone -->
            <div class="form-row">
                <div class="form-group">
                    <label for="nom_demandeur">Nom et prénom *</label>
                    <input type="text" id="nom_demandeur" name="nom_demandeur" value="{{Auth::user()->name}}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="email_demandeur">Email *</label>
                    <input type="email" id="email_demandeur" name="email_demandeur" value="{{Auth::user()->email}}" readonly>
                </div>
                
                <div class="form-group">
                    <label for="telephone_demandeur">Téléphone *</label>
                    <input type="tel" id="telephone_demandeur" name="telephone_demandeur" value="{{Auth::user()->contact}}" readonly>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" id="btn-recapitulatif" class="btn-recapitulatif">
                <span class="btn-icon">📋</span>
               Soumettre la demande
            </button>
        </div>
    </form>
</div>

<!-- Modal de récapitulatif -->
<div id="recapModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Récapitulatif de votre demande</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div id="recapContent">
                <!-- Le contenu du récapitulatif sera généré ici -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="btn-modifier" class="btn-modifier">
                <span class="btn-icon">✏️</span>
                Modifier
            </button>
            <button type="button" id="btn-confirmer" class="btn-confirmer">
                <span class="btn-icon">✓</span>
                Confirmer et soumettre
            </button>
        </div>
    </div>
</div>

<style>
    .jours-selection {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
    
    .jour-checkbox {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .jour-checkbox:hover {
        background: #e9ecef;
    }
    
    .jour-checkbox input[type="checkbox"] {
        display: none;
    }
    
    .jour-checkbox input[type="checkbox"]:checked + .checkmark {
        background: #f35525;
        border-color: #f35525;
    }
    
    .jour-checkbox input[type="checkbox"]:checked + .checkmark::after {
        content: '✓';
        color: white;
        font-size: 12px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid #ced4da;
        border-radius: 4px;
        margin-right: 10px;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .date-checkbox {
        display: flex;
        align-items: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .date-checkbox:hover {
        background: #e9ecef;
    }
    
    .date-checkbox input[type="checkbox"] {
        margin-right: 8px;
    }
    
    #montant-details {
        color: #f35525;
        font-weight: 500;
    }

    textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 16px;
        color: #2d3748;
        background-color: #fff;
        transition: all 0.3s ease;
        resize: vertical;
        min-height: 120px;
        box-sizing: border-box;
    }
    
    textarea:focus {
        outline: none;
        border-color: #f35525;
        box-shadow: 0 0 0 3px rgba(243, 85, 37, 0.1);
    }
    
    textarea::placeholder {
        color: #a0aec0;
    }
    
    .form-group textarea {
        margin-top: 8px;
    }

    /* Styles pour les lignes de formulaire */
    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-row1 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Styles pour le bouton récapitulatif */
    .btn-recapitulatif {
        background: #f35525;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-right: 10px;
    }

    .btn-recapitulatif:hover {
        background: #5a6268;
    }

    /* Styles pour la modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #f35525, #ff6b4a);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5em;
    }

    .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }

    .close:hover {
        color: #f0f0f0;
    }

    .modal-body {
        padding: 30px;
        max-height: 60vh;
        overflow-y: auto;
    }

    .recap-section {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .recap-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .recap-section h3 {
        color: #f35525;
        margin-bottom: 15px;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .recap-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding: 8px 0;
    }

    .recap-label {
        font-weight: 600;
        color: #495057;
    }

    .recap-value {
        color: #6c757d;
        text-align: right;
        max-width: 60%;
    }

    .recap-jours {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
        margin-top: 10px;
    }

    .modal-footer {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .btn-modifier {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-modifier:hover {
        background: #5a6268;
    }

    .btn-confirmer {
        background: #28a745;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-confirmer:hover {
        background: #218838;
    }

    .btn-icon {
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        .form-row1 {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        textarea {
            font-size: 14px;
            min-height: 100px;
        }

        .modal-content {
            width: 95%;
            margin: 10% auto;
        }

        .modal-footer {
            flex-direction: column;
        }

        .recap-item {
            flex-direction: column;
            gap: 5px;
        }

        .recap-value {
            max-width: 100%;
            text-align: left;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ---- ÉLÉMENTS DU DOM ----
    const messeForm = document.getElementById('messeForm');
    const villeSelect = document.getElementById('ville_id');
    const communeSelect = document.getElementById('commune_id');
    const paroisseSelect = document.getElementById('paroisse_id');
    const celebrationSelect = document.getElementById('celebration_choisie');
    const dateSouhaiteeInput = document.getElementById('date_souhaitee');
    const montantOffrandeInput = document.getElementById('montant_offrande');
    const montantDetails = document.getElementById('montant-details');
    const joursQuotidienneDiv = document.getElementById('jours_messe_quotidienne');
    const joursDominicaleDiv = document.getElementById('jours_messe_dominicale');
    const dimanchesContainer = document.getElementById('dimanches-container');

    // Modal elements
    const modal = document.getElementById('recapModal');
    const btnRecapitulatif = document.getElementById('btn-recapitulatif');
    const closeBtn = document.querySelector('.modal .close');
    const btnModifier = document.getElementById('btn-modifier');
    const btnConfirmer = document.getElementById('btn-confirmer');

    // ---- ÉTAT DE L'APPLICATION ----
    let montantUnitaire = 0;

    // =====================================================================
    // ---- INITIALISATION ----
    // =====================================================================
    
    // Définir la date minimale pour la date de début au jour actuel
    const today = new Date().toISOString().split('T')[0];
    dateSouhaiteeInput.setAttribute('min', today);

    // =====================================================================
    // ---- GESTION DES LISTES DÉROULANTES DÉPENDANTES ----
    // =====================================================================

    villeSelect.addEventListener('change', function() {
        const villeId = this.value;
        resetSelect(communeSelect, "Sélectionnez d'abord une ville");
        resetSelect(paroisseSelect, "Sélectionnez d'abord une commune");
        montantUnitaire = 0;
        calculerMontantTotal();

        if (villeId) {
            fetch(`/get-communes/${villeId}`)
                .then(response => response.json())
                .then(data => populateSelect(communeSelect, data, 'Sélectionnez une commune', 'id', 'nom_commune'));
        }
    });

    communeSelect.addEventListener('change', function() {
        const communeId = this.value;
        resetSelect(paroisseSelect, "Sélectionnez d'abord une commune");
        montantUnitaire = 0;
        calculerMontantTotal();
        
        if (communeId) {
            fetch(`/get-paroisses/${communeId}`)
                .then(response => response.json())
                .then(data => populateSelect(paroisseSelect, data, 'Sélectionnez une paroisse', 'id', 'name', { 'data-montant': 'montant_offrande' }));
        }
    });

    paroisseSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        montantUnitaire = parseFloat(selectedOption.getAttribute('data-montant')) || 0;
        calculerMontantTotal();
    });

    // =====================================================================
    // ---- GESTION DES CHAMPS CONDITIONNELS (TYPE DE CÉLÉBRATION) ----
    // =====================================================================

    celebrationSelect.addEventListener('change', function() {
        joursQuotidienneDiv.style.display = 'none';
        joursDominicaleDiv.style.display = 'none';

        if (this.value === 'Messe quotidienne') {
            joursQuotidienneDiv.style.display = 'block';
        } else if (this.value === 'Messe dominicale') {
            genererDimanches();
            joursDominicaleDiv.style.display = 'block';
        }
        calculerMontantTotal();
    });
    
    // =====================================================================
    // ---- CALCUL DU MONTANT TOTAL ----
    // =====================================================================
    
    function calculerMontantTotal() {
        if (montantUnitaire === 0) {
            montantOffrandeInput.value = '';
            montantDetails.textContent = 'Veuillez sélectionner une paroisse pour voir le montant.';
            return;
        }

        let nombreJoursSelectionnes = 0;
        const celebrationType = celebrationSelect.value;
        
        if (celebrationType === 'Messe quotidienne') {
            nombreJoursSelectionnes = document.querySelectorAll('input[name="jours_quotidienne[]"]:checked').length;
        } else if (celebrationType === 'Messe dominicale') {
            nombreJoursSelectionnes = document.querySelectorAll('input[name="jours_dominicale[]"]:checked').length;
        } else if (celebrationType === 'Messe solennelle') {
            nombreJoursSelectionnes = 1;
        }
        
        const total = montantUnitaire * nombreJoursSelectionnes;
        montantOffrandeInput.value = total.toFixed(0);
        
        if (nombreJoursSelectionnes > 0) {
            montantDetails.textContent = `Détail : ${montantUnitaire.toFixed(0)} FCFA × ${nombreJoursSelectionnes} jour(s) = ${total.toFixed(0)} FCFA`;
        } else {
            montantDetails.textContent = `Montant par messe : ${montantUnitaire.toFixed(0)} FCFA. Sélectionnez des jours.`;
        }
    }

    // Recalculer le montant à chaque fois qu'une case est cochée/décochée
    messeForm.addEventListener('change', function(e) {
        if (e.target.matches('input[name="jours_quotidienne[]"]') || e.target.matches('input[name="jours_dominicale[]"]')) {
            calculerMontantTotal();
        }
    });

    // =====================================================================
    // ---- GESTION DE LA MODAL DE RÉCAPITULATIF ----
    // =====================================================================
    
    btnRecapitulatif.addEventListener('click', function() {
        if (validerFormulaire()) {
            genererRecapitulatif();
            modal.style.display = 'block';
        }
    });

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    btnModifier.addEventListener('click', () => modal.style.display = 'none');
    btnConfirmer.addEventListener('click', () => messeForm.submit());
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // =====================================================================
    // ---- FONCTIONS DE LOGIQUE (VALIDATION, GÉNÉRATION) ----
    // =====================================================================

    function validerFormulaire() {
        let isValid = true;
        let firstInvalidField = null;

        // Validation des champs requis standards
        messeForm.querySelectorAll('[required]').forEach(field => {
            field.style.borderColor = ''; // Reset style
            if (!field.value.trim() && field.offsetParent !== null) { // Check if field is visible
                isValid = false;
                field.style.borderColor = 'red';
                if (!firstInvalidField) firstInvalidField = field;
            }
        });

        // Validation spécifique pour les jours de messe
        const celebrationType = celebrationSelect.value;
        if (celebrationType === 'Messe quotidienne' && document.querySelectorAll('input[name="jours_quotidienne[]"]:checked').length === 0) {
            isValid = false;
            joursQuotidienneDiv.style.border = '1px solid red';
            if (!firstInvalidField) firstInvalidField = joursQuotidienneDiv;
        } else {
            joursQuotidienneDiv.style.border = '';
        }
        
        if (celebrationType === 'Messe dominicale' && document.querySelectorAll('input[name="jours_dominicale[]"]:checked').length === 0) {
            isValid = false;
            joursDominicaleDiv.style.border = '1px solid red';
            if (!firstInvalidField) firstInvalidField = joursDominicaleDiv;
        } else {
            joursDominicaleDiv.style.border = '';
        }

        if (!isValid && firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            alert('Veuillez remplir tous les champs obligatoires.');
        }

        return isValid;
    }

    function genererRecapitulatif() {
        const recapContent = document.getElementById('recapContent');
        const formData = new FormData(messeForm);
        
        const ville = villeSelect.options[villeSelect.selectedIndex]?.text || 'N/A';
        const commune = communeSelect.options[communeSelect.selectedIndex]?.text || 'N/A';
        const paroisse = paroisseSelect.options[paroisseSelect.selectedIndex]?.text || 'N/A';

        let joursSelectionnesHTML = '';
        if (formData.get('celebration_choisie') === 'Messe quotidienne') {
            const jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            const joursCoches = Array.from(document.querySelectorAll('input[name="jours_quotidienne[]"]:checked')).map(cb => `<div>• ${jours[cb.value - 1]}</div>`).join('');
            if(joursCoches) joursSelectionnesHTML = `<div class="recap-item"><span class="recap-label">Jours sélectionnés :</span><div class="recap-value recap-jours">${joursCoches}</div></div>`;
        } else if (formData.get('celebration_choisie') === 'Messe dominicale') {
            const dimanchesCoches = Array.from(document.querySelectorAll('input[name="jours_dominicale[]"]:checked')).map(cb => `<div>• ${formatDateDisplay(cb.value)}</div>`).join('');
            if(dimanchesCoches) joursSelectionnesHTML = `<div class="recap-item"><span class="recap-label">Dimanches sélectionnés :</span><div class="recap-value recap-jours">${dimanchesCoches}</div></div>`;
        }

        recapContent.innerHTML = `
            <div class="recap-section">
                <h3>🙏 Intention de la messe</h3>
                <div class="recap-item"><span class="recap-label">Motif :</span><span class="recap-value">${formData.get('motif_intention')}</span></div>
                <div class="recap-item"><span class="recap-label">Intercession par :</span><span class="recap-value">${formData.get('interception_par') || 'Non spécifié'}</span></div>
            </div>
            <div class="recap-section">
                <h3>⛪ Détails de la messe</h3>
                <div class="recap-item"><span class="recap-label">Lieu :</span><span class="recap-value">${ville} > ${commune} > ${paroisse}</span></div>
                <div class="recap-item"><span class="recap-label">Type de célébration :</span><span class="recap-value">${formData.get('celebration_choisie')}</span></div>
                ${joursSelectionnesHTML}
                <div class="recap-item"><span class="recap-label">Date de début :</span><span class="recap-value">${formatDateDisplay(formData.get('date_souhaitee'))}</span></div>
                <div class="recap-item"><span class="recap-label">Heure souhaitée :</span><span class="recap-value">${formData.get('heure_souhaitee') || 'Non spécifiée'}</span></div>
                <div class="recap-item"><span class="recap-label">Montant total :</span><span class="recap-value" style="color: #f35525; font-weight: bold;">${formData.get('montant_offrande') || '0'} FCFA</span></div>
            </div>
            <div class="recap-section">
                <h3>👤 Informations du demandeur</h3>
                <div class="recap-item"><span class="recap-label">Nom et prénom :</span><span class="recap-value">${formData.get('nom_demandeur')}</span></div>
                <div class="recap-item"><span class="recap-label">Email :</span><span class="recap-value">${formData.get('email_demandeur')}</span></div>
                <div class="recap-item"><span class="recap-label">Téléphone :</span><span class="recap-value">${formData.get('telephone_demandeur')}</span></div>
            </div>
        `;
    }

    function genererDimanches() {
        dimanchesContainer.innerHTML = '';
        let dateCourante = new Date();
        if (dateSouhaiteeInput.value) { // Start from the selected date if available
            dateCourante = new Date(dateSouhaiteeInput.value);
        }
        dateCourante.setHours(0, 0, 0, 0);

        // Trouver le prochain dimanche (ou le jour même si c'est un dimanche)
        dateCourante.setDate(dateCourante.getDate() + (7 - dateCourante.getDay()) % 7);

        for (let i = 0; i < 4; i++) {
            const dateStr = dateCourante.toISOString().split('T')[0];
            const formattedDate = dateCourante.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' });
            dimanchesContainer.innerHTML += `<label class="date-checkbox"><input type="checkbox" name="jours_dominicale[]" value="${dateStr}">${formattedDate}</label>`;
            dateCourante.setDate(dateCourante.getDate() + 7);
        }
    }


    // =====================================================================
    // ---- FONCTIONS UTILITAIRES ----
    // =====================================================================

    function resetSelect(selectElement, defaultText) {
        selectElement.innerHTML = `<option value="">${defaultText}</option>`;
        selectElement.disabled = true;
    }

    function populateSelect(selectElement, data, placeholder, valueKey, textKey, dataAttributes = {}) {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = item[textKey];
            for (const attr in dataAttributes) {
                option.setAttribute(attr, item[dataAttributes[attr]]);
            }
            selectElement.appendChild(option);
        });
        selectElement.disabled = false;
    }

    function formatDateDisplay(dateString) {
        if (!dateString) return 'Non spécifiée';
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    }

});
</script>
@endsection