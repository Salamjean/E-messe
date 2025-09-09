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
        
        <div class="radio-group">
    <label class="radio-label">Type d'intention *</label>
    <div class="radio-options">
        <label class="radio-option">
            <input type="radio" name="type_intention" value="Defunt" class="intention-radio">
            <span class="radio-custom"></span>
            <span class="radio-text">Défunt</span>
        </label>
        <label class="radio-option">
            <input type="radio" name="type_intention" value="Action graces" class="intention-radio">
            <span class="radio-custom"></span>
            <span class="radio-text">Action de grâces</span>
        </label>
        <label class="radio-option">
            <input type="radio" name="type_intention" value="Intention particuliere" class="intention-radio">
            <span class="radio-custom"></span>
            <span class="radio-text">Intention particulière</span>
        </label>
    </div>
</div>

<!-- Champs conditionnels selon le type d'intention -->
<div id="defunt_fields" class="conditional-field">
    <div class="form-group">
        <label for="nom_defunt">Motif de demande *</label>
        <textarea id="nom_defunt" name="nom_defunt" rows="4" placeholder="Entrez les détails concernant le défunt">{{old('nom_defunt')}}</textarea>
        @error('nom_defunt')
            <div class="error-message" style="color: rgb(184, 8, 8)">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div id="action_graces_fields" class="conditional-field">
    <div class="form-group">
        <label for="motif_action_graces">Motif de l'action de grâces *</label>
        <textarea id="motif_action_graces" name="motif_action_graces" rows="4" placeholder="Décrivez les raisons de votre action de grâces">{{old('motif_action_graces')}}</textarea>
        @error('motif_action_graces')
            <div class="error-message" style="color: rgb(184, 8, 8)">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div id="intention_particuliere_fields" class="conditional-field">
    <div class="form-group">
        <label for="motif_intention">Motif de l'intention particulière *</label>
        <textarea id="motif_intention" name="motif_intention" rows="4" placeholder="Précisez votre intention particulière">{{old('motif_intention')}}</textarea>
        @error('motif_intention')
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
            <div class="form-group">
                    <label for="paroisse_id">Paroisse *</label>
                    <select id="paroisse_id" name="paroisse_id" required>
                        <option value="">Sélectionnez une paroisse</option>
                        @foreach($paroisses as $paroisse)
                            <option value="{{ $paroisse->id }}" data-montant="{{ $paroisse->montant_offrande }}">{{ $paroisse->name }}</option>
                        @endforeach
                    </select>
                    @error('paroisse_id')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                <label for="celebration_choisie">Type de célébration *</label>
                <select id="celebration_choisie" name="celebration_choisie"  required>
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
                    @error('jours_quotidienne')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div id="jours_messe_dominicale" class="conditional-field">
                <div class="form-group">
                    <label>Dimanches du mois *</label>
                    <div class="jours-selection" id="dimanches-container">
                        <!-- Les dimanches seront générés dynamiquement en JS -->
                    </div>
                    @error('jours_dominicale')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="montant_offrande">Montant des demandes de messes (FCFA)</label>
                    <div class="input-with-icon">
                        <input type="number" id="montant_offrande" name="montant_offrande" step="0.01" min="0" readonly>
                    </div>
                    <small id="montant-details">Ce montant est suggéré par la paroisse sélectionnée</small>
                </div>
                <div class="form-group">
                    <label for="nom_prenom_concernes">Noms et prénoms des concernés *</label>
                    <div id="noms-container">
                        <div class="nom-input-group">
                            <input type="text" name="nom_prenom_concernes[]" value="{{old('nom_prenom_concernes')}}" class="nom-input" required>
                            <button type="button" class="add-nom-btn">+</button>
                        </div>
                    </div>
                    <small>Cliquez sur "+" pour ajouter un autre nom</small>
                    @error('nom_prenom_concernes')
                        <div class="error-message" style="color: rgb(184, 8, 8)">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_souhaitee">Date de début *</label>
                    <input type="date" id="date_souhaitee" value="{{old('date_souhaitee')}}" name="date_souhaitee">
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
            
            <div class="form-group">
                <label for="nom_demandeur">Nom et prénom *</label>
                <input type="text" id="nom_demandeur" name="nom_demandeur" value="{{Auth::user()->name}}" readonly>
            </div>
            
            <div class="form-row">
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
            <button type="submit" class="btn-submit">
                <span class="btn-icon">✓</span>
                Soumettre la demande
            </button>
        </div>
    </form>
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
    
    /* Styles pour les textarea */
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
    
    /* Responsive adjustment */
    @media (max-width: 768px) {
        textarea {
            font-size: 14px;
            min-height: 100px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        let montantUnitaire = 0;
        let nombreJoursSelectionnes = 0;
        
        // Gestion des champs conditionnels selon le type d'intention
        const intentionRadios = document.querySelectorAll('.intention-radio');
        const conditionalFields = document.querySelectorAll('.conditional-field');
        
        function toggleConditionalFields() {
            // Cacher tous les champs conditionnels
            conditionalFields.forEach(field => {
                field.style.display = 'none';
            });
            
            // Afficher les champs correspondant à la sélection
            const selectedValue = document.querySelector('input[name="type_intention"]:checked').value;
            
            if (selectedValue === 'Defunt') {
                document.getElementById('defunt_fields').style.display = 'block';
            } else if (selectedValue === 'Action graces') {
                document.getElementById('action_graces_fields').style.display = 'block';
            } else if (selectedValue === 'Intention particuliere') {
                document.getElementById('intention_particuliere_fields').style.display = 'block';
            }
        }
        
        // Écouter les changements sur les radios
        intentionRadios.forEach(radio => {
            radio.addEventListener('change', toggleConditionalFields);
        });
        
        // Sélectionner le premier radio par défaut et déclencher l'événement
        if (intentionRadios.length > 0) {
            intentionRadios[0].checked = true;
            toggleConditionalFields();
        }
        
        // Gestion du type de célébration
        const celebrationSelect = document.getElementById('celebration_choisie');
        const joursQuotidienne = document.getElementById('jours_messe_quotidienne');
        const joursDominicale = document.getElementById('jours_messe_dominicale');
        
        celebrationSelect.addEventListener('change', function() {
            // Cacher tous les champs de jours
            joursQuotidienne.style.display = 'none';
            joursDominicale.style.display = 'none';
            
            // Afficher les champs correspondants
            if (this.value === 'Messe quotidienne') {
                joursQuotidienne.style.display = 'block';
            } else if (this.value === 'Messe dominicale') {
                joursDominicale.style.display = 'block';
                genererDimanches();
            }
            
            // Recalculer le montant
            calculerMontantTotal();
        });
        
        // Générer les dimanches du mois en cours
        function genererDimanches() {
            const container = document.getElementById('dimanches-container');
            container.innerHTML = '';
            
            const aujourdhui = new Date();
            const annee = aujourdhui.getFullYear();
            const mois = aujourdhui.getMonth();
            
            // Premier jour du mois
            const premierJour = new Date(annee, mois, 1);
            
            // Dernier jour du mois
            const dernierJour = new Date(annee, mois + 1, 0);
            
            // Trouver tous les dimanches du mois
            let dateCourante = new Date(premierJour);
            let dimanches = [];
            
            while (dateCourante <= dernierJour) {
                if (dateCourante.getDay() === 0) { // 0 = Dimanche
                    dimanches.push(new Date(dateCourante));
                }
                dateCourante.setDate(dateCourante.getDate() + 1);
            }
            
            // Créer les checkboxes pour chaque dimanche
            dimanches.forEach(date => {
                const dateStr = date.toISOString().split('T')[0];
                const formattedDate = formatDate(date);
                
                const label = document.createElement('label');
                label.className = 'date-checkbox';
                
                label.innerHTML = `
                    <input type="checkbox" name="jours_dominicale[]" value="${dateStr}" onchange="calculerMontantTotal()">
                    ${formattedDate}
                `;
                
                container.appendChild(label);
            });
        }
        
        // Formater une date en français
        function formatDate(date) {
            const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            return date.toLocaleDateString('fr-FR', options);
        }
        
        // Gestion du montant d'offrande basé sur la paroisse sélectionnée
        const paroisseSelect = document.getElementById('paroisse_id');
        const montantOffrandeInput = document.getElementById('montant_offrande');
        const montantDetails = document.getElementById('montant-details');
        
        paroisseSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const montant = selectedOption.getAttribute('data-montant');
            
            if (montant) {
                montantUnitaire = parseFloat(montant);
                calculerMontantTotal();
            } else {
                montantUnitaire = 0;
                montantOffrandeInput.value = '';
                montantDetails.textContent = 'Veuillez sélectionner une paroisse';
            }
        });
        
        // Calculer le montant total en fonction des jours sélectionnés
        function calculerMontantTotal() {
            if (montantUnitaire === 0) {
                montantOffrandeInput.value = '';
                return;
            }
            
            let total = 0;
            const celebrationType = celebrationSelect.value;
            
            if (celebrationType === 'Messe quotidienne') {
                const joursSelectionnes = document.querySelectorAll('input[name="jours_quotidienne[]"]:checked');
                total = montantUnitaire * joursSelectionnes.length;
                nombreJoursSelectionnes = joursSelectionnes.length;
            } 
            else if (celebrationType === 'Messe dominicale') {
                const joursSelectionnes = document.querySelectorAll('input[name="jours_dominicale[]"]:checked');
                total = montantUnitaire * joursSelectionnes.length;
                nombreJoursSelectionnes = joursSelectionnes.length;
            }
            else if (celebrationType === 'Messe solennelle') {
                total = montantUnitaire;
                nombreJoursSelectionnes = 1;
            }
            else {
                total = 0;
                nombreJoursSelectionnes = 0;
            }
            
            montantOffrandeInput.value = total.toFixed(2);
            
            // Mettre à jour les détails du montant
            if (nombreJoursSelectionnes > 0) {
                montantDetails.textContent = `${montantUnitaire.toFixed(2)} FCFA × ${nombreJoursSelectionnes} = ${total.toFixed(2)} FCFA`;
            } else {
                montantDetails.textContent = 'Sélectionnez au moins un jour';
            }
        }
        
        // Écouter les changements sur les checkboxes de jours
        document.addEventListener('change', function(e) {
            if (e.target.name === 'jours_quotidienne[]' || e.target.name === 'jours_dominicale[]') {
                calculerMontantTotal();
            }
        });
        
        // Déclencher l'événement au chargement si une paroisse est déjà sélectionnée
        if (paroisseSelect.value) {
            paroisseSelect.dispatchEvent(new Event('change'));
        }
        
        // Gestion de l'ajout de noms supplémentaires
        const nomsContainer = document.getElementById('noms-container');
        
        function addNomField(value = '') {
            const nomGroup = document.createElement('div');
            nomGroup.className = 'nom-input-group';
            
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'nom_prenom_concernes[]';
            input.className = 'nom-input';
            input.value = value;
            input.required = true;
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-nom-btn';
            removeBtn.innerHTML = '−';
            removeBtn.addEventListener('click', function() {
                nomGroup.remove();
            });
            
            nomGroup.appendChild(input);
            nomGroup.appendChild(removeBtn);
            nomsContainer.appendChild(nomGroup);
        }
        
        // Écouter le clic sur le bouton d'ajout
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('add-nom-btn')) {
                addNomField();
            }
        });
        
        // Validation avant soumission du formulaire
        document.getElementById('messeForm').addEventListener('submit', function(e) {
            // Vérifier qu'au moins un nom est rempli
            const nomInputs = document.querySelectorAll('.nom-input');
            let auMoinsUnNomRempli = false;
            
            nomInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    auMoinsUnNomRempli = true;
                }
            });
            
            if (!auMoinsUnNomRempli) {
                e.preventDefault();
                alert('Veuillez saisir au moins un nom concerné.');
                return false;
            }
            
            // Vérifier la sélection des jours selon le type de célébration
            const celebrationType = celebrationSelect.value;
            let joursSelectionnes = 0;
            
            if (celebrationType === 'Messe quotidienne') {
                joursSelectionnes = document.querySelectorAll('input[name="jours_quotidienne[]"]:checked').length;
            } 
            else if (celebrationType === 'Messe dominicale') {
                joursSelectionnes = document.querySelectorAll('input[name="jours_dominicale[]"]:checked').length;
            }
            else if (celebrationType === 'Messe solennelle') {
                joursSelectionnes = 1; // Pas de sélection de jours pour les messes solennelles
            }
            
            if (celebrationType !== 'Messe solennelle' && joursSelectionnes === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un jour pour la célébration.');
                return false;
            }
            
            // S'assurer que tous les champs requis sont remplis
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                    
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    
                    field.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.style.borderColor = '';
                        }
                    });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }
                alert('Veuillez remplir tous les champs obligatoires.');
                return false;
            }
        });
        
        // Exposer la fonction au scope global pour les checkboxes
        window.calculerMontantTotal = calculerMontantTotal;
    });
</script>
@endsection