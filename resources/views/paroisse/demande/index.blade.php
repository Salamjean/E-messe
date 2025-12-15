@extends('paroisse.layouts.template')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endsection

@section('content')
    <div class="container-fluid mt-4"><br><br>

        <div class="messe-header">
            <h1>Liste des demandes de messes à célébrer</h1>
            <p class="text-muted" style="color: #1b63a2ff;">Consultez, filtrez et exportez toutes les intentions de messes.
            </p>
        </div>

        <!-- Zone d'actions groupées -->
        <div class="bulk-actions" id="bulkActions">
            <span class="fw-bold me-3 text-primary">
                <i class="fas fa-check-circle"></i> <span id="selectedCount">0</span> sélectionné(s)
            </span>

            <button type="button" class="btn btn-primary btn-sm" onclick="confirmAndGeneratePDF()">
                📄 Générer la feuille de messe (PDF)
            </button>

            <button type="button" class="btn btn-outline-secondary btn-sm ms-auto" onclick="deselectAll()">
                ❌ Tout désélectionner
            </button>
        </div>

        <!-- Tableau DataTable -->
        <table id="messesTable" class="table table-hover table-striped dt-responsive nowrap" style="width:100%">
            <thead>
                <tr class="table-light">
                    <th width="5%" class="text-center">
                        <input type="checkbox" id="selectAllHeader" class="form-check-input" style="cursor: pointer;">
                    </th>
                    <th>Date de soumission</th>
                    <th>Type celebration</th>
                    <th>Noms concernés</th>
                    <th>Date Souhaitée</th>
                    <th>Progression</th>
                    <th>Montant messes</th>
                    <th width="5%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Chargement via Ajax -->
            </tbody>
        </table>
    </div>

    <!-- Formulaire caché pour l'export PDF -->
    <form id="pdfForm" action="{{ route('paroisse.messe.export-pdf') }}" method="POST" target="_blank">
        @csrf
        <input type="hidden" name="selected_ids" id="selectedIds">
    </form>

    <!-- Modal de confirmation -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">📄 Génération du PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">Vous allez générer le document pour les messes sélectionnées.</p>
                    <p class="text-muted mb-0">
                        ⚠️ Cette action passera automatiquement le statut des messes confirmées à
                        <span class="badge badge-celebre">Célébrée</span>.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="proceedWithPDF()">
                        Confirmer et Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Bibliothèques JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configuration DataTables
            var table = $('#messesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: "{{ route('demandes.messes.index') }}",
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'date_creation'
                    },
                    {
                        data: 'type_celebration'
                    },
                    {
                        data: 'noms'
                    },
                    {
                        data: 'date_souhaitee'
                    },
                    {
                        data: 'progression',
                        orderable: false
                    }, // Barre de progression
                    {
                        data: 'montant'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                },
                order: [
                    [1, 'desc']
                ], // Trier par date de création (plus récent en haut)
                drawCallback: function() {
                    // Restaure l'état des checkboxes si on change de page (optionnel)
                    updateBulkActions();
                }
            });

            // --- GESTION DES CHECKBOXES ---

            // Clic sur "Tout sélectionner" dans l'en-tête
            $('#selectAllHeader').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.messe-checkbox').prop('checked', isChecked);
                updateBulkActions();
            });

            // Clic sur une checkbox individuelle
            $('#messesTable tbody').on('change', '.messe-checkbox', function() {
                updateBulkActions();

                // Mettre à jour l'état du "Tout sélectionner"
                var allChecked = $('.messe-checkbox:checked').length === $('.messe-checkbox').length;
                $('#selectAllHeader').prop('checked', allChecked);
            });
        });

        // Fonction d'affichage de la barre d'actions
        function updateBulkActions() {
            var selectedCount = $('.messe-checkbox:checked').length;
            $('#selectedCount').text(selectedCount);

            if (selectedCount > 0) {
                $('#bulkActions').css('display', 'flex'); // Flex pour l'alignement
            } else {
                $('#bulkActions').hide();
            }
        }

        function deselectAll() {
            $('.messe-checkbox').prop('checked', false);
            $('#selectAllHeader').prop('checked', false);
            updateBulkActions();
        }

        // --- GESTION DU PDF ET MODAL ---

        function confirmAndGeneratePDF() {
            var selectedCount = $('.messe-checkbox:checked').length;
            if (selectedCount === 0) {
                alert('Veuillez sélectionner au moins une demande.');
                return;
            }
            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            myModal.show();
        }

        function proceedWithPDF() {
            // 1. Récupération des IDs
            var selectedIds = [];
            $('.messe-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            // UI Feedback
            var updateStatusUrl = '{{ route('paroisse.messe.update-status') }}';
            var modalEl = document.getElementById('confirmationModal');
            var modal = bootstrap.Modal.getInstance(modalEl);

            // 2. Appel Ajax pour mettre à jour les statuts
            $.ajax({
                url: updateStatusUrl,
                method: 'POST',
                data: {
                    selected_ids: JSON.stringify(selectedIds),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    modal.hide();

                    if (response.success) {
                        // 3. Soumission du formulaire PDF (ouverture nouvel onglet)
                        $('#selectedIds').val(JSON.stringify(selectedIds));
                        $('#pdfForm').submit();

                        // 4. Recharger le tableau pour voir les nouveaux statuts (Célébrée)
                        // Petit délai pour laisser le temps au PDF de se lancer
                        setTimeout(function() {
                            $('#messesTable').DataTable().ajax.reload(null,
                                false); // false = garder la pagination
                            deselectAll();
                        }, 1000);
                    } else {
                        alert('Erreur: ' + response.error);
                    }
                },
                error: function(xhr) {
                    modal.hide();
                    alert('Une erreur est survenue lors de la mise à jour des statuts.');
                }
            });
        }
    </script>
@endpush

<style>
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

    .messe-container {
        width: 95%;
        margin: 0 auto;
        padding: 30px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }


    .messe-header {
        margin-bottom: 30px;
        text-align: center;
    }

    table.dataTable thead th {
        background-color: #5ea7b5;
        color: #ffffffff !important;
        vertical-align: middle;
    }

    .messe-header h1 {
        color: #d4bd8a;
        /* Votre couleur principale */
        font-weight: 700;
        margin-bottom: 10px;
    }

    /* Styles des badges (doivent correspondre aux classes du Controller) */
    .badge-confirmee {
        background-color: #d4edda;
        color: #155724;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-en_attente {
        background-color: #fff3cd;
        color: #856404;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-celebre {
        background-color: #d1ecf1;
        color: #0c5460;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-annulee {
        background-color: #f8d7da;
        color: #721c24;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Actions groupées */
    .bulk-actions {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: none;
        /* Masqué par défaut */
        align-items: center;
        gap: 10px;
        border: 1px solid #dee2e6;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    table.dataTable td {
        vertical-align: middle;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
    }

    /* Fond du tableau */
    #messesTable {
        background-color: #117aaeff;
    }

    /* Fond des lignes */
    #messesTable tbody tr {
        background-color: #ffffff;
    }

    /* Fond de l'en-tête */
    #messesTable thead tr {
        background-color: #d8eff5 !important;
    }
</style>
