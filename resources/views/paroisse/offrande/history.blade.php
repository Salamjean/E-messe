@extends('paroisse.layouts.template')

@section('styles')
    <!-- CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <div class="messe-header">
            <h1>Historique célébrées ou annulées</h1>
            <p>Retrouvez toutes les demandes traitées.</p>
        </div>

        <!-- Actions de masse (PDF) -->
        <div class="bulk-actions" id="bulkActionsPanel">
            <span id="selectedCount" style="font-weight: bold; color: #333;">0 sélectionné(s)</span>
            <button onclick="generatePDF()" class="btn-action btn-pdf">
                📄 Générer le PDF
            </button>
        </div>

        <!-- Tableau DataTable -->
        <div class="table-responsive bg-white p-3 rounded shadow-sm">
            <table id="messesTable" class="table table-striped table-hover display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="selectAll"></th>
                        <th>Date Création</th>
                        <th>Type</th>
                        <th>Date Souhaitée</th>
                        <th>Heure</th>
                        <th>Montant</th>
                        <th>Demandeur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- MODAL DE DÉTAILS -->
    <div class="modal fade" id="messeDetailModal" tabindex="-1" role="dialog" aria-labelledby="messeDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messeDetailModalLabel">Détails de la demande</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        onclick="$('#messeDetailModal').modal('hide')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Statut -->
                    <div class="text-center mb-4" id="modalStatusContainer">
                        <!-- Badge injecté via JS -->
                    </div>

                    <h5>📅 Informations Générales</h5>
                    <div class="detail-row">
                        <span class="detail-label">Créée le :</span>
                        <span class="detail-value" id="modalDateCreation"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type de messe :</span>
                        <span class="detail-value" id="modalTypeMesse"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Demandeur :</span>
                        <span class="detail-value" id="modalDemandeur"></span>
                    </div>

                    <h5 class="mt-4">⛪ Célébration</h5>
                    <div class="detail-row">
                        <span class="detail-label">Date souhaitée :</span>
                        <span class="detail-value" id="modalDateSouhaitee"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Heure :</span>
                        <span class="detail-value" id="modalHeure"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Offrande :</span>
                        <span class="detail-value" id="modalMontant" style="color: #28a745; font-weight: bold;"></span>
                    </div>

                    <h5 class="mt-4">🙏 Intentions (Noms concernés)</h5>
                    <div class="intentions-box" id="modalIntentions">
                        <!-- Noms injectés via JS -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="$('#messeDetailModal').modal('hide')">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire caché pour l'export PDF -->
    <form id="pdfForm" action="{{ route('paroisse.messe.export-pdf') }}" method="POST" target="_blank">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds">
    </form>
@endsection

@push('js')
    <!-- jQuery (Déjà inclus ?) -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> -->
    <!-- Bootstrap JS (Nécessaire pour le modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialisation du DataTable
            var table = $('#messesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('demandes.messes.history') }}",
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                },
                order: [
                    [1, "desc"]
                ],
                columns: [{
                        data: 'id',
                        orderable: false,
                        render: function(data) {
                            return `<input type="checkbox" class="messe-checkbox" value="${data}">`;
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY');
                        }
                    },
                    {
                        data: 'celebration_choisie'
                    }, // Assurez-vous que cette colonne existe dans votre JSON
                    {
                        data: 'date_souhaitee',
                        render: function(data) {
                            return moment(data).format('DD/MM/YYYY');
                        }
                    },
                    {
                        data: 'heure_souhaitee'
                    },
                    {
                        data: 'montant_offrande',
                        render: function(data) {
                            return data ? new Intl.NumberFormat('fr-FR').format(data) + ' FCFA' :
                                '-';
                        }
                    },
                    {
                        data: 'nom_demandeur'
                    },
                    {
                        data: null, // On passe l'objet entier (null data source)
                        orderable: false,
                        render: function(data, type, row) {
                            // Bouton Voir avec classe spéciale pour trigger le modal
                            let buttons =
                                `<button class="btn-action btn-view view-details-btn" title="Voir les détails">👁️</button>`;

                            // Bouton Annuler
                            if (row.statut === 'en attente') {
                                let cancelUrl =
                                    "{{ route('paroisse.messe.cancel', ['messe' => ':id']) }}"
                                    .replace(':id', row.id);
                                let csrf = "{{ csrf_token() }}";
                                buttons += `
                                    <form action="${cancelUrl}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr ?')">
                                        <input type="hidden" name="_token" value="${csrf}">
                                        <button type="submit" class="btn-action" style="background:#dc3545; color:white;" title="Annuler">🗑️</button>
                                    </form>
                                `;
                            }
                            return buttons;
                        }
                    }
                ]
            });

            // --- LOGIQUE DU MODAL ---

            // Délégation d'événement pour le bouton "Voir"
            $('#messesTable tbody').on('click', '.view-details-btn', function() {
                // Récupérer les données de la ligne
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var data = row.data();

                // 1. Remplir le statut (Badge)
                var statusClass = 'badge-' + data.statut.replace(' ', '_').toLowerCase();
                var statusLabel = data.statut.charAt(0).toUpperCase() + data.statut.slice(1);
                $('#modalStatusContainer').html(
                    `<span class="badge-status ${statusClass}" style="font-size:1rem; padding: 8px 20px;">${statusLabel}</span>`
                );

                // 2. Remplir les champs textes
                $('#modalDateCreation').text(moment(data.created_at).format('DD/MM/YYYY à HH:mm'));
                $('#modalTypeMesse').text(data.celebration_choisie || data.type_intention);
                $('#modalDemandeur').text(data.nom_demandeur);

                $('#modalDateSouhaitee').text(moment(data.date_souhaitee).format('DD/MM/YYYY'));
                $('#modalHeure').text(data.heure_souhaitee || 'Non spécifiée');
                $('#modalMontant').text(new Intl.NumberFormat('fr-FR').format(data.montant_offrande) +
                    ' FCFA');

                // 3. Traitement des Intentions (Noms concernés)
                var intentionsHtml = '';
                var noms = [];

                // Logique de parsing robuste (JSON ou String ou Array)
                // Note: Assurez-vous que le JSON renvoyé par le contrôleur contient 'nom_prenom_concernes'
                // Si la colonne n'est pas affichée dans le tableau, elle est quand même dans l'objet 'data'
                var rawNoms = data.nom_prenom_concernes;

                try {
                    if (Array.isArray(rawNoms)) {
                        noms = rawNoms;
                    } else if (typeof rawNoms === 'string') {
                        // Test si c'est du JSON
                        if (rawNoms.startsWith('[') || rawNoms.startsWith('{')) {
                            noms = JSON.parse(rawNoms);
                        } else {
                            noms = [rawNoms];
                        }
                    }
                    // Forcer en tableau si objet simple
                    if (noms && !Array.isArray(noms)) noms = [noms];
                } catch (e) {
                    noms = [rawNoms];
                }

                if (noms && noms.length > 0) {
                    noms.forEach(function(nom) {
                        intentionsHtml += `<span class="nom-tag">${nom}</span> `;
                    });
                } else {
                    intentionsHtml = '<span class="text-muted">Aucun nom spécifié</span>';
                }

                $('#modalIntentions').text(data.motif_intention || 'Non spécifiée');
                // $('#modalHeure').text(data.heure_souhaitee || 'Non spécifiée');

                // 4. Afficher le modal
                $('#messeDetailModal').modal('show');
            });


            // --- LOGIQUE DES CHECKBOXES ---

            $('#selectAll').on('click', function() {
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                updateBulkActions();
            });

            $('#messesTable tbody').on('change', 'input[type="checkbox"]', function() {
                if (!this.checked) {
                    var el = $('#selectAll').get(0);
                    if (el && el.checked && ('indeterminate' in el)) el.indeterminate = true;
                }
                updateBulkActions();
            });

            function updateBulkActions() {
                let count = $('.messe-checkbox:checked').length;
                if (count > 0) {
                    $('#bulkActionsPanel').css('display', 'flex');
                    $('#selectedCount').text(count + ' demande(s) sélectionnée(s)');
                } else {
                    $('#bulkActionsPanel').hide();
                }
            }

            window.generatePDF = function() {
                let selectedIds = [];
                $('.messe-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert('Veuillez sélectionner au moins une ligne.');
                    return;
                }
                $('#selectedIds').val(JSON.stringify(selectedIds));
                $('#pdfForm').submit();
            }
        });
    </script>
@endpush

<style>
    .messe-container {
        width: 95%;
        margin: 0 auto;
        padding: 20px 0;
    }

    .messe-header {
        text-align: center;
        margin-bottom: 30px;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .messe-header h1 {
        color: #d4bd8a;
        font-weight: 700;
    }

    /* Badges de statut */
    .badge-status {
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-confirmee {
        background: #d4edda;
        color: #155724;
    }

    .badge-celebre {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-annulee {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-en_attente {
        background: #fff3cd;
        color: #856404;
    }

    /* Actions groupées */
    .bulk-actions {
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        display: none;
        align-items: center;
        gap: 15px;
        border: 1px solid #e9ecef;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-view {
        background-color: #c49d54;
        color: white;
    }

    .btn-view:hover {
        background-color: #a38243;
        color: white;
    }

    .btn-pdf {
        background-color: #5ea7b5;
        color: white;
    }

    .btn-pdf:hover {
        background-color: #4a8996;
    }

    /* DataTable Customization */
    table.dataTable thead th {
        background-color: #5ea7b5;
        color: #ffffff !important;
        vertical-align: middle;
        padding: 12px;
    }

    table.dataTable tbody td {
        vertical-align: middle;
    }

    .nom-tag {
        display: inline-block;
        background: #f0f2f5;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        margin: 2px;
        color: #495057;
        border: 1px solid #e9ecef;
    }

    /* Styles Spécifiques Modal */
    .modal-header {
        background-color: #5ea7b5;
        color: white;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 700;
    }

    .modal-body h5 {
        color: #c49d54;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }

    .detail-row {
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
    }

    .detail-label {
        font-weight: 600;
        color: #666;
    }

    .detail-value {
        font-weight: 500;
        color: #333;
        text-align: right;
    }

    .intentions-box {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
</style>
