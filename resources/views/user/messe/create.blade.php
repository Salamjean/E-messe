@extends('user.layouts.template')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="container-fluid">
        <!-- Stepper Header -->
        <div class="stepper-wrapper">
            <div class="stepper-item active" data-step="1">
                <div class="step-counter">1</div>
                <div class="step-name">Intention de prière</div>
            </div>
            <div class="stepper-item" data-step="2">
                <div class="step-counter">2</div>
                <div class="step-name">Offrez une messe</div>
            </div>
            <div class="stepper-item" data-step="3">
                <div class="step-counter">3</div>
                <div class="step-name">Confirmation</div>
            </div>
            <div class="stepper-line"></div>
        </div>

        <!-- Affichage des erreurs globales -->
        @if ($errors->any())
            <div class="alert alert-danger" style="max-width: 800px; margin: 0 auto 20px auto;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" style="max-width: 800px; margin: 0 auto 20px auto;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('user.messe.store') }}" method="POST" class="messe-form" id="messeForm">
            @csrf

            <!-- STEP 1: Intention -->
            <div class="step-content active" id="step-1">
                <div class="form-section-clean">
                    <h2>Formulez votre intention de prière</h2>

                    <div class="form-group">
                        <label for="motif_intention">Motif de la demande *</label>
                        <textarea id="motif_intention" name="motif_intention" rows="4" value="{{ old('motif_intention') }}"
                            placeholder="Précisez l'intention de votre messe (défunt, action de grâces, intention particulière, etc.)" required>{{ old('motif_intention') }}</textarea>
                        @error('motif_intention')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="interception_par">Par l'intercession de ... (Optionnel)</label>
                        <input type="text" id="interception_par" name="interception_par"
                            value="{{ old('interception_par') }}" placeholder="Ex: Saint Marie...">
                        @error('interception_par')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions-right">
                    <button type="button" class="btn-next" onclick="nextStep(2)">Suivant</button>
                </div>
            </div>

            <!-- STEP 2: Détails -->
            <div class="step-content" id="step-2">
                <div class="form-section-clean">
                    <h2>Détails de la messe</h2>

                    <!-- Localisation -->
                    <div class="form-row-custom">
                        <div class="form-group">
                            <label for="ville_id">Ville *</label>
                            <select id="ville_id" name="ville_id" value="{{ old('ville_id') }}" required>
                                <option value="">Sélectionnez une ville</option>
                                @foreach ($villes as $ville)
                                    <option value="{{ $ville->id }}">{{ $ville->nom_ville }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="commune_id">Commune *</label>
                            <select id="commune_id" name="commune_id" value="{{ old('commune_id') }}" required disabled>
                                <option value="">Sélectionnez d'abord une ville</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="paroisse_id">Paroisse *</label>
                        <select id="paroisse_id" name="paroisse_id" value="{{ old('paroisse_id') }}" required disabled>
                            <option value="">Sélectionnez d'abord une commune</option>
                        </select>
                        @error('paroisse_id')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type, Date, Heure -->
                    <div class="form-row-custom three-cols">
                        <div class="form-group">
                            <label for="celebration_choisie">Type de célébration *</label>
                            <select id="celebration_choisie" name="celebration_choisie"
                                value="{{ old('celebration_choisie') }}" required>
                                <option value="">Sélectionnez une option</option>
                                <option value="Messe quotidienne">Messe quotidienne</option>
                                <option value="Messe dominicale">Messe dominicale</option>
                                <option value="Messe solennelle">Messe solennelle</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_souhaitee">Date *</label>
                            <input type="date" id="date_souhaitee" name="date_souhaitee"
                                value="{{ old('date_souhaitee') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="heure_souhaitee">Heure (Optionnel)</label>
                            <input type="time" id="heure_souhaitee" name="heure_souhaitee"
                                value="{{ old('heure_souhaitee') }}">
                        </div>
                    </div>

                    <!-- Champs conditionnels -->
                    <div id="jours_messe_quotidienne" class="conditional-field">
                        <div class="form-group">
                            <label>Jours de la semaine *</label>
                            <div class="jours-selection">
                                @php $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']; @endphp
                                @foreach ($jours as $index => $jour)
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
                            <div class="jours-selection" id="dimanches-container"></div>
                        </div>
                    </div>

                    <!-- Montant -->
                    <div class="form-group montant-group">
                        <label>Montant estimé</label>
                        <div class="input-with-icon">
                            <input type="text" id="montant_offrande_display" readonly
                                placeholder="Calculé automatiquement">
                            <input type="hidden" id="montant_offrande" name="montant_offrande">
                        </div>
                        <small id="montant-details">Sélectionnez une paroisse et des jours pour voir le montant.</small>
                    </div>

                </div>

                <div class="form-actions-between">
                    <button type="button" class="btn-prev" onclick="prevStep(1)">Précédent</button>
                    <button type="button" class="btn-next" onclick="nextStep(3)">Suivant</button>
                </div>
            </div>

            <!-- STEP 3: Confirmation -->
            <div class="step-content" id="step-3">
                <div class="form-section-clean">
                    <h2>Confirmation de votre demande</h2>

                    <div class="recap-container" id="recapContent">
                        <!-- Filled by JS -->
                    </div>

                    <div class="user-info-summary">
                        <h3>Vos coordonnées</h3>
                        <div class="form-group mb-3">
                            <label for="nom_demandeur">Nom complet *</label>
                            <input type="text" id="nom_demandeur" name="nom_demandeur"
                                value="{{ old('nom_demandeur', Auth::user()->name) }}" required>
                        </div>
                        <div class="form-row-custom">
                            <div class="form-group mb-3">
                                <label for="email_demandeur">Email *</label>
                                <input type="email" id="email_demandeur" name="email_demandeur"
                                    value="{{ old('email_demandeur', Auth::user()->email) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="telephone_demandeur">Téléphone *</label>
                                <input type="text" id="telephone_demandeur" name="telephone_demandeur"
                                    value="{{ old('telephone_demandeur', Auth::user()->contact) }}" required
                                    maxlength="20">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions-between">
                    <button type="button" class="btn-prev" onclick="prevStep(2)">Précédent</button>
                    <button type="submit" class="btn-confirm">Confirmer et payer</button>
                </div>
            </div>

        </form>
    </div>

    <style>
        .messe-form::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        /* Stepper */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            position: relative;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .stepper-line {
            position: absolute;
            top: 20px;
            /* Center with circle */
            left: 50px;
            right: 50px;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
        }

        .stepper-item {
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1;
            background: transparent;
            /* cover line */
            padding: 0 10px;
            cursor: default;
        }

        .step-counter {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #757575;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .step-name {
            font-weight: 500;
            color: #757575;
            display: none;
            /* Hide on small screens, show on lg */
        }

        @media(min-width: 768px) {
            .step-name {
                display: block;
            }
        }

        .stepper-item.active .step-counter {
            background: #d4af37;
            /* Gold/Brown theme */
            color: white;
        }

        .stepper-item.active .step-name {
            color: #333;
            font-weight: bold;
        }

        .stepper-item.completed .step-counter {
            background: #4caf50;
            color: white;
        }

        /* Form Sections */
        .step-content {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        .step-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section-clean {
            margin-bottom: 30px;
        }

        .form-section-clean h2 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }

        .form-row-custom {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row-custom.three-cols {
            grid-template-columns: 1fr 1fr 1fr;
        }

        @media(max-width: 768px) {

            .form-row-custom,
            .form-row-custom.three-cols {
                grid-template-columns: 1fr;
            }
        }

        /* Inputs */
        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #d4af37;
        }

        /* Buttons */
        .form-actions-right {
            display: flex;
            justify-content: flex-end;
        }

        .form-actions-between {
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            transition: transform 0.1s;
        }

        button:active {
            transform: scale(0.98);
        }

        .btn-next,
        .btn-confirm {
            background: #d4af37;
            color: white;
            font-weight: bold;
        }

        .btn-next:hover,
        .btn-confirm:hover {
            background: #c09d2e;
        }

        .btn-prev {
            background: #f5f5f5;
            color: #666;
            border: 1px solid #ddd;
        }

        .btn-prev:hover {
            background: #e0e0e0;
        }

        /* Conditional checkboxes */
        .jours-selection {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .jour-checkbox,
        .date-checkbox {
            background: #f9f9f9;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            border: 1px solid #eee;
            user-select: none;
        }

        .jour-checkbox input,
        .date-checkbox input {
            display: none;
        }

        .jour-checkbox.selected,
        .date-checkbox.selected {
            background: #fff3cd;
            border-color: #d4af37;
            color: #856404;
        }

        /* Recap Styles */
        .recap-container {
            background: #fdfdfd;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .recap-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 10px;
        }

        .recap-row:last-child {
            border-bottom: none;
        }

        .recap-label {
            color: #666;
        }

        .recap-val {
            font-weight: 600;
            color: #333;
            text-align: right;
        }

        .user-info-summary {
            background: #fafafa;
            padding: 15px;
            border-radius: 10px;
        }

        .user-info-summary h3 {
            margin-top: 0;
            font-size: 1.1rem;
        }

        .user-info-summary p {
            margin: 5px 0;
            color: #555;
        }

        .error-message {
            color: red;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Hidden by default */
        .conditional-field {
            display: none;
            margin-top: 15px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- State ---
            let currentStep = 1;
            const totalSteps = 3;
            let montantUnitaire = 0;

            // --- DOM Elements ---
            const form = document.getElementById('messeForm');
            const villeSelect = document.getElementById('ville_id');
            const communeSelect = document.getElementById('commune_id');
            const paroisseSelect = document.getElementById('paroisse_id');
            const celebrationSelect = document.getElementById('celebration_choisie');
            const dateSouhaiteeInput = document.getElementById('date_souhaitee');
            const montantOffrandeInput = document.getElementById('montant_offrande');
            const montantOffrandeDisplay = document.getElementById('montant_offrande_display');
            const montantDetails = document.getElementById('montant-details');
            const joursQuotidienneDiv = document.getElementById('jours_messe_quotidienne');
            const joursDominicaleDiv = document.getElementById('jours_messe_dominicale');
            const dimanchesContainer = document.getElementById('dimanches-container');
            const recapContent = document.getElementById('recapContent');

            // --- Initialization ---
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            dateSouhaiteeInput.setAttribute('min', tomorrowStr);

            // --- Navigation Logic ---
            window.nextStep = function(step) {
                if (!validateStep(currentStep)) return;

                // Helper to prepare next step data
                if (step === 3) {
                    genererRecapitulatif();
                }

                showStep(step);
            }

            window.prevStep = function(step) {
                showStep(step);
            }

            function showStep(step) {
                // Update Step Content
                document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
                document.getElementById('step-' + step).classList.add('active');

                // Update Stepper UI
                document.querySelectorAll('.stepper-item').forEach(el => {
                    const s = parseInt(el.dataset.step);
                    el.classList.remove('active', 'completed');
                    if (s === step) el.classList.add('active');
                    if (s < step) el.classList.add('completed');
                });

                currentStep = step;
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            function validateStep(step) {
                let isValid = true;
                const stepEl = document.getElementById('step-' + step);
                const requiredInputs = stepEl.querySelectorAll('[required]');

                requiredInputs.forEach(input => {
                    if (!input.value.trim() && !input.disabled && input.offsetParent !== null) {
                        input.style.borderColor = 'red';
                        isValid = false;
                    } else {
                        input.style.borderColor = '#ddd';
                    }
                });

                // Custom validation for checkboxes
                if (step === 2) {
                    const type = celebrationSelect.value;
                    if (type === 'Messe quotidienne') {
                        const checked = document.querySelectorAll('input[name="jours_quotidienne[]"]:checked');
                        if (checked.length === 0) {
                            joursQuotidienneDiv.querySelector('.jours-selection').style.border = '1px solid red';
                            isValid = false;
                        } else {
                            joursQuotidienneDiv.querySelector('.jours-selection').style.border = 'none';
                        }
                    } else if (type === 'Messe dominicale') {
                        const checked = document.querySelectorAll('input[name="jours_dominicale[]"]:checked');
                        if (checked.length === 0) {
                            joursDominicaleDiv.querySelector('.jours-selection').style.border = '1px solid red';
                            isValid = false;
                        } else {
                            joursDominicaleDiv.querySelector('.jours-selection').style.border = 'none';
                        }
                    }
                }

                if (!isValid) alert('Veuillez remplir tous les champs obligatoires.');
                return isValid;
            }

            // --- Checkbox Styling Logic ---
            document.body.addEventListener('change', function(e) {
                if (e.target.matches('input[type="checkbox"]')) {
                    const parent = e.target.parentElement; // .jour-checkbox or .date-checkbox
                    if (e.target.checked) parent.classList.add('selected');
                    else parent.classList.remove('selected');
                }
            });

            // --- Dynamic Dropdowns ---
            villeSelect.addEventListener('change', function() {
                const villeId = this.value;
                resetSelect(communeSelect, "Sélectionnez d'abord une ville");
                resetSelect(paroisseSelect, "Sélectionnez d'abord une commune");
                montantUnitaire = 0;
                calculerMontantTotal();

                if (villeId) {
                    fetch(`/get-communes/${villeId}`)
                        .then(r => r.json())
                        .then(data => populateSelect(communeSelect, data, 'Sélectionnez une commune', 'id',
                            'nom_commune'));
                }
            });

            communeSelect.addEventListener('change', function() {
                const communeId = this.value;
                resetSelect(paroisseSelect, "Sélectionnez d'abord une commune");
                montantUnitaire = 0;
                calculerMontantTotal();

                if (communeId) {
                    fetch(`/get-paroisses/${communeId}`)
                        .then(r => r.json())
                        .then(data => populateSelect(paroisseSelect, data, 'Sélectionnez une paroisse',
                            'id', 'name', {
                                'data-montant': 'montant_offrande'
                            }));
                }
            });

            paroisseSelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                montantUnitaire = parseFloat(opt.getAttribute('data-montant')) || 0;
                calculerMontantTotal();
            });

            // --- Celebration Type Logic ---
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

            form.addEventListener('change', function(e) {
                if (e.target.matches('input[name="jours_quotidienne[]"]') || e.target.matches(
                        'input[name="jours_dominicale[]"]')) {
                    calculerMontantTotal();
                }
            });

            function calculerMontantTotal() {
                if (montantUnitaire === 0) {
                    montantOffrandeDisplay.value = '';
                    montantDetails.innerText = 'Sélectionnez une paroisse pour voir le tarif.';
                    return;
                }

                let count = 0;
                const type = celebrationSelect.value;
                if (type === 'Messe quotidienne') count = document.querySelectorAll(
                    'input[name="jours_quotidienne[]"]:checked').length;
                else if (type === 'Messe dominicale') count = document.querySelectorAll(
                    'input[name="jours_dominicale[]"]:checked').length;
                else if (type === 'Messe solennelle') count = 1;

                const totalOffrande = montantUnitaire * count;

                // Calcul des frais: 4% avec minimum 200 FCFA
                let frais = 0;
                if (totalOffrande > 0) {
                    frais = Math.max(totalOffrande * 0.04, 200);
                }
                const totalAvecFrais = totalOffrande + frais;

                montantOffrandeInput.value = totalOffrande;
                montantOffrandeDisplay.value = totalOffrande > 0 ? totalOffrande + ' FCFA' : '';

                if (count > 0) {
                    let detailsText = `${montantUnitaire} FCFA x ${count} = ${totalOffrande} FCFA`;
                    detailsText += ` + Frais de service: ${frais} FCFA`;
                    montantDetails.innerText = detailsText;
                    montantDetails.innerHTML =
                        `${detailsText} <br><strong>Total à payer: ${totalAvecFrais} FCFA</strong>`;
                } else montantDetails.innerText = `Tarif unitaire: ${montantUnitaire} FCFA.`;
            }

            function genererDimanches() {
                dimanchesContainer.innerHTML = '';
                let d = dateSouhaiteeInput.value ? new Date(dateSouhaiteeInput.value) : new Date();
                d.setHours(0, 0, 0, 0);
                // Find next Sunday
                d.setDate(d.getDate() + (7 - d.getDay()) % 7);
                if (d < new Date().setHours(0, 0, 0, 0)) d.setDate(d.getDate() +
                    7); // Ensure future if today is passed

                for (let i = 0; i < 4; i++) {
                    const str = d.toISOString().split('T')[0];
                    const label = d.toLocaleDateString('fr-FR', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long'
                    });
                    const div = document.createElement('label');
                    div.className = 'date-checkbox';
                    div.innerHTML = `<input type="checkbox" name="jours_dominicale[]" value="${str}"> ${label}`;
                    dimanchesContainer.appendChild(div);
                    d.setDate(d.getDate() + 7);
                }
            }

            // Re-run genererDimanches if date changes (optional but good ux)
            dateSouhaiteeInput.addEventListener('change', function() {
                if (celebrationSelect.value === 'Messe dominicale') genererDimanches();
            });

            // --- Recap Logic ---
            function genererRecapitulatif() {
                const ville = villeSelect.options[villeSelect.selectedIndex]?.text || '';
                const paroisse = paroisseSelect.options[paroisseSelect.selectedIndex]?.text || '';
                const type = celebrationSelect.value;
                const date = dateSouhaiteeInput.value;
                const heure = document.getElementById('heure_souhaitee').value || 'Non spécifiée';

                const motif = document.getElementById('motif_intention').value;
                const intercession = document.getElementById('interception_par').value || 'Non spécifié';
                const nom = document.getElementById('nom_demandeur').value;
                const email = document.getElementById('email_demandeur').value;
                const tel = document.getElementById('telephone_demandeur').value;

                let joursStr = '';
                if (type === 'Messe quotidienne') {
                    const days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                    document.querySelectorAll('input[name="jours_quotidienne[]"]:checked').forEach(cb => {
                        joursStr += days[cb.value - 1] + ', ';
                    });
                } else if (type === 'Messe dominicale') {
                    document.querySelectorAll('input[name="jours_dominicale[]"]:checked').forEach(cb => {
                        joursStr += cb.parentElement.innerText + ', ';
                    });
                }
                if (joursStr) joursStr = joursStr.slice(0, -2);

                const montantOffrande = parseFloat(montantOffrandeInput.value) || 0;
                const frais = montantOffrande > 0 ? Math.max(montantOffrande * 0.04, 200) : 0;
                const montantTotal = montantOffrande + frais;

                recapContent.innerHTML = '';

                const addRow = (label, val, style = '') => {
                    if (val === undefined || val === null) return;
                    const div = document.createElement('div');
                    div.className = 'recap-row';
                    div.innerHTML =
                        `<span class="recap-label">${label}</span><span class="recap-val" style="${style}"></span>`;
                    div.querySelector('.recap-val').textContent = val;
                    recapContent.appendChild(div);
                };

                addRow('Motif', motif);
                addRow('Intercession', intercession);
                addRow('Paroisse', `${ville} - ${paroisse}`);
                addRow('Célébration', type);
                if (joursStr) addRow('Jours', joursStr);
                addRow('Début', `${date} à ${heure}`);
                addRow('Demandeur', `${nom} (${tel})`);
                addRow('Montant Offrande', montantOffrande + ' FCFA');
                addRow('Frais de service (4% - min 200F)', frais + ' FCFA');
                addRow('Montant Total à payer', montantTotal + ' FCFA',
                    'color:#d4af37; font-size:1.1em; font-weight: bold;');
            }

            // --- Utils ---
            function resetSelect(sel, place) {
                sel.innerHTML = `<option value="">${place}</option>`;
                sel.disabled = true;
            }

            function populateSelect(sel, data, place, vk, tk, attrs = {}) {
                sel.innerHTML = `<option value="">${place}</option>`;
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item[vk];
                    opt.textContent = item[tk];
                    for (let k in attrs) opt.setAttribute(k, item[attrs[k]]);
                    sel.appendChild(opt);
                });
                sel.disabled = false;
            }
        });
    </script>
@endsection
