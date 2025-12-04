@extends('paroisse.layouts.template')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="messe-container">
        <div class="messe-header">
            <h1>Validation des intentions de messses</h1>
            <p>Retrouvez toutes vos demandes en attente de validatation.</p>
        </div>

        @if ($filteredMessess->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">⛪</div>
                <h3>Aucune demande de messe</h3>
            </div>
        @else
            <!-- Section de sélection globale -->
            <div class="select-all-container">
                <label class="checkbox-label">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                    <span class="checkmark"></span>
                    <span style="margin-left: 5px;">Tout sélectionner</span>
                </label>
            </div>

            <!-- Actions groupées -->
            <div id="bulkActions" class="bulk-actions" style="display: none; margin-bottom: 20px;">
                <span id="selectedCount" style="font-weight: 500; color: #333;"></span>
                <button type="button" class="btn btn-success" onclick="bulkConfirm()">
                    ✅ Confirmer la sélection
                </button>
                <button type="button" class="btn btn-danger" onclick="bulkCancel()">
                    ❌ Annuler la sélection
                </button>
                <button type="button" class="btn btn-secondary" onclick="deselectAll()">
                    🔄 Tout désélectionner
                </button>
            </div>

            <div class="messe-cards">
                @foreach ($filteredMessess as $messe)
                    <div class="messe-card" data-status="{{ $messe->statut }}">

                        <!-- Checkbox pour sélection individuelle -->
                        <div class="card-checkbox">
                            <label class="checkbox-label">
                                <input type="checkbox" class="messe-checkbox" value="{{ $messe->id }}"
                                    onchange="updateBulkActions()">
                                <span class="checkmark"></span>
                            </label>
                        </div>

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
                                Messe
                                @if ($messe->type_intention === 'Defunt')
                                    de Défunt
                                @elseif($messe->type_intention === 'Action graces')
                                    d'action de Grâces
                                @else
                                    d'ntention Particulière
                                @endif
                            </h3>

                            <div class="card-details">
                                <div class="detail-item">
                                    <span class="detail-label">📅 Date souhaitée:</span>
                                    <span
                                        class="detail-value">{{ \Carbon\Carbon::parse($messe->date_souhaitee)->format('d/m/Y') }}</span>
                                </div>

                                @if ($messe->heure_souhaitee)
                                    <div class="detail-item">
                                        <span class="detail-label">⏰ Heure:</span>
                                        <span class="detail-value">{{ $messe->heure_souhaitee }}</span>
                                    </div>
                                @endif

                                <div class="detail-item">
                                    <span class="detail-label">⛪ Type:</span>
                                    <span class="detail-value">{{ $messe->celebration_choisie ?? 'Non spécifié' }}</span>
                                </div>

                                @if ($messe->montant_offrande)
                                    <div class="detail-item">
                                        <span class="detail-label">💰 Offrande:</span>
                                        <span
                                            class="detail-value">{{ number_format($messe->montant_offrande, 0, ',', ' ') }}
                                            FCFA</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-noms">
                                <strong>Noms concernés:</strong>
                                @php
                                    $noms = is_array($messe->nom_prenom_concernes)
                                        ? $messe->nom_prenom_concernes
                                        : json_decode($messe->nom_prenom_concernes, true) ?? [
                                                $messe->nom_prenom_concernes,
                                            ];
                                @endphp
                                <div class="noms-list">
                                    @foreach ($noms as $nom)
                                        <span class="nom-tag">{{ $nom }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('paroisse.messe.show', ['messe' => $messe->id]) }}"
                                class="card-action-btn view-btn">
                                👁️ Voir
                            </a>
                            @if ($messe->statut === 'en attente')
                                <form action="{{ route('paroisse.messe.confirmed', ['messe' => $messe->id]) }}"
                                    method="POST" class="d-inline" onsubmit="return confirmAction(event, 'confirmer');">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="card-action-btn confirm-btn">
                                        ✅ Confirmer
                                    </button>
                                </form>
                                <form action="{{ route('paroisse.messe.cancel', ['messe' => $messe->id]) }}" method="POST"
                                    class="d-inline" onsubmit="return confirmAction(event, 'annuler');">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="card-action-btn cancel-btn">
                                        ❌ Annuler
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Formulaire caché pour les actions groupées -->
    <form id="bulkActionForm" action="" method="POST" style="display: none;">
        @csrf
        @method('POST')
        <input type="hidden" name="selected_ids" id="bulkSelectedIds">
    </form>

    <style>
        .messe-container {
            width: 90%;
            margin: 0 auto;
            padding: 0 20px;
        }

        .messe-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .messe-header h1 {
            color: #5ea7b5;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .messe-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .messe-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 0px;
        }

        .messe-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #5ea7b5;
            position: relative;
            width: 300px;
            height: 100%;
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
            background: #c49d54;
            color: white;
        }

        .view-btn:hover {
            background: #ff7c52;
            color: white;
        }

        .confirm-btn {
            background: #339c5d;
            color: white;
        }

        .confirm-btn:hover {
            background: #218838;
            color: white;
        }

        .cancel-btn {
            background: #de353e;
            color: white;
        }

        .cancel-btn:hover {
            background: #de353e;
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

        .checkbox-label input:checked~.checkmark {
            background-color: #2196F3;
            border-color: #2196F3;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-label input:checked~.checkmark:after {
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
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .bulk-actions .btn {
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .bulk-actions .btn-success {
            background: #28a745;
            color: white;
        }

        .bulk-actions .btn-success:hover {
            background: #218838;
        }

        .bulk-actions .btn-danger {
            background: #dc3545;
            color: white;
        }

        .bulk-actions .btn-danger:hover {
            background: #c82333;
        }

        .bulk-actions .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .bulk-actions .btn-secondary:hover {
            background: #545b62;
        }

        .select-all-container {
            background: white;
            padding: 12px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            display: inline-block;
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

            .select-all-container {
                width: 100%;
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
            const selectedCountSpan = document.getElementById('selectedCount');
            const selectAllCheckbox = document.getElementById('selectAll');

            if (selectedCount > 0) {
                bulkActions.style.display = 'flex';
                selectedCountSpan.textContent = `${selectedCount} demande(s) sélectionnée(s)`;
            } else {
                bulkActions.style.display = 'none';
            }

            // Mettre à jour la case "Tout sélectionner"
            const totalCheckboxes = document.querySelectorAll('.messe-checkbox').length;
            selectAllCheckbox.checked = selectedCount === totalCheckboxes && totalCheckboxes > 0;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCheckboxes;
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

        // Fonction pour confirmer plusieurs demandes
        function bulkConfirm() {
            const selectedCheckboxes = document.querySelectorAll('.messe-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                Swal.fire({
                    title: 'Aucune sélection',
                    text: 'Veuillez sélectionner au moins une demande.',
                    icon: 'warning',
                    confirmButtonColor: '#f35525'
                });
                return;
            }

            Swal.fire({
                title: 'Confirmer les demandes',
                html: `Vous allez confirmer <strong>${selectedIds.length} demande(s)</strong>. Cette action est irréversible.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mettre à jour le formulaire et soumettre
                    document.getElementById('bulkSelectedIds').value = JSON.stringify(selectedIds);
                    document.getElementById('bulkActionForm').action =
                        "{{ route('paroisse.messe.bulk-confirm') }}";
                    document.getElementById('bulkActionForm').submit();
                }
            });
        }

        // Fonction pour annuler plusieurs demandes
        function bulkCancel() {
            const selectedCheckboxes = document.querySelectorAll('.messe-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);

            if (selectedIds.length === 0) {
                Swal.fire({
                    title: 'Aucune sélection',
                    text: 'Veuillez sélectionner au moins une demande.',
                    icon: 'warning',
                    confirmButtonColor: '#f35525'
                });
                return;
            }

            Swal.fire({
                title: 'Annuler les demandes',
                html: `Vous allez annuler <strong>${selectedIds.length} demande(s)</strong>. Cette action est irréversible.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, annuler',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mettre à jour le formulaire et soumettre
                    document.getElementById('bulkSelectedIds').value = JSON.stringify(selectedIds);
                    document.getElementById('bulkActionForm').action = "{{ route('paroisse.messe.bulk-cancel') }}";
                    document.getElementById('bulkActionForm').submit();
                }
            });
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

        function confirmAction(event, action) {
            event.preventDefault();
            const form = event.target.closest('form');

            if (action === 'confirmer') {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Vous allez confirmer cette demande de messe!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, confirmer!',
                    cancelButtonText: 'Non, annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else if (action === 'annuler') {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Vous allez annuler cette demande de messe!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, annuler!',
                    cancelButtonText: 'Non, garder'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        }
    </script>
@endsection
