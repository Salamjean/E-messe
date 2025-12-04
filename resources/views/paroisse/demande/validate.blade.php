@extends('paroisse.layouts.template')

@section('content')
    <!-- CSS DataTables & SweetAlert -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="messe-container">
        <div class="messe-header">
            <h1>Validation des intentions de messes</h1>
            <p>Gérez vos demandes en attente via le tableau ci-dessous.</p>
        </div>

        <!-- Actions groupées -->
        <div id="bulkActions" class="bulk-actions" style="display: none;">
            <span id="selectedCount" class="fw-bold me-3">0 sélectionné(s)</span>
            <button type="button" class="btn btn-success btn-sm" onclick="triggerBulkAction('confirm')">
                ✅ Tout Confirmer
            </button>
            <button type="button" class="btn btn-danger btn-sm" onclick="triggerBulkAction('cancel')">
                ❌ Tout Annuler
            </button>
        </div>

        <!-- Tableau DataTables -->
        <div class="table-responsive bg-white p-3 rounded shadow-sm">
            <table id="messesTable" class="table table-striped table-hover display responsive nowrap" style="width:100%">
                <thead style="color: #000000ff;">
                    <tr>
                        <th width="5%"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                        <th>Date Demande</th>
                        <th>Type celebration</th>
                        <th>Noms concernés</th>
                        <th>Date Souhaitée</th>
                        <th>Heure souhaitée</th>
                        <th>Montant messes</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL POUR AFFICHER LES DETAILS (SHOW)     -->
    <!-- ========================================== -->
    <div class="modal fade" id="showMesseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #5ea7b5; color: white;">
                    <h5 class="modal-title">Détails de l'intention</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Zone de chargement (spinner) -->
                    <div id="modalLoader" class="text-center py-5">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>

                    <!-- Contenu des détails (caché par défaut) -->
                    <div id="modalContent" style="display: none;">
                        <div class="row g-3">
                            <!-- Bloc Gauche -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-uppercase text-muted small fw-bold">Informations Célébration</h6>
                                <div class="mb-3">
                                    <label class="fw-bold text-dark">Type :</label>
                                    <span id="show_type_intention" class="d-block text-primary"></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-dark">Date souhaitée :</label>
                                    <span id="show_date_souhaitee" class="d-block"></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-dark">Heure :</label>
                                    <span id="show_heure" class="d-block"></span>
                                </div>
                            </div>

                            <!-- Bloc Droite -->
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted small fw-bold">Données Demandeur</h6>
                                <div class="mb-3">
                                    <label class="fw-bold text-dark">Demandé par :</label>
                                    <span id="show_demandeur" class="d-block"></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-bold text-dark">Offrande :</label>
                                    <span id="show_offrande" class="d-block fs-5 fw-bold" style="color: #d4bd8a;"></span>
                                </div>
                            </div>

                            <!-- Bloc Bas (Large) -->
                            <div class="col-12 mt-3">
                                <div class="p-3 bg-light rounded border border-info">
                                    <label class="fw-bold text-muted mb-2">Motif de l'intention :</label>
                                    <p id="motif_intention" class="mb-0 fs-5" style="white-space: pre-line; color:#333;">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const URL_BULK_CONFIRM = "{{ route('paroisse.messe.bulk-confirm') }}";
        const URL_BULK_CANCEL = "{{ route('paroisse.messe.bulk-cancel') }}";
        const URL_LOAD_DATA = "{{ route('demandes.messes.validate') }}";

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#messesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: URL_LOAD_DATA,
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'date_creation'
                    },
                    {
                        data: 'type_intention'
                    },
                    {
                        data: 'noms',
                        render: function(d) {
                            return d && d.length > 50 ? d.substr(0, 50) + '...' : d;
                        }
                    },
                    {
                        data: 'date_souhaitee'
                    },
                    {
                        data: 'heure'
                    },
                    {
                        data: 'offrande'
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
                ],
                drawCallback: function() {
                    updateBulkActions();
                }
            });

            // --- GESTION DES CHECKBOXES ---
            $('#selectAll').on('change', function() {
                $('.messe-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkActions();
            });

            $('#messesTable tbody').on('change', '.messe-checkbox', function() {
                updateBulkActions();
                $('#selectAll').prop('checked', $('.messe-checkbox:checked').length === $('.messe-checkbox')
                    .length);
            });

            function updateBulkActions() {
                var count = $('.messe-checkbox:checked').length;
                $('#selectedCount').text(count + ' demande(s) sélectionnée(s)');
                (count > 0) ? $('#bulkActions').slideDown(): $('#bulkActions').slideUp();
            }

            // --- ACTION INDIVIDUELLE (CONFIRM/CANCEL) ---
            $('#messesTable tbody').on('click', '.confirm-single-btn', function() {
                executeAjax($(this).data('url'), [], 'confirm', 'Cette demande sera confirmée.');
            });
            $('#messesTable tbody').on('click', '.cancel-single-btn', function() {
                executeAjax($(this).data('url'), [], 'cancel', 'Cette demande sera annulée.');
            });

            // ==========================================
            // --- ACTION AFFICHER (SHOW_DETAILS) ---
            // ==========================================
            // On cible bien la classe .btn-show-details ajoutée dans le controller
            $('#messesTable tbody').on('click', '.btn-show-details', function() {
                var url = $(this).data('url');

                // 1. Initialiser le modal
                var myModal = new bootstrap.Modal(document.getElementById('showMesseModal'));

                // 2. Afficher le loader, cacher le contenu
                $('#modalLoader').show();
                $('#modalContent').hide();

                // 3. Ouvrir le modal
                myModal.show();

                // 4. Appel Ajax
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        var data = response.data || response;

                        // Remplissage des champs
                        $('#show_type_intention').text(data.type_intention || 'Non défini');
                        $('#show_date_souhaitee').text(data.date_souhaitee || '-');
                        $('#show_heure').text(data.heure || '-');
                        $('#show_offrande').text(data.offrande + ' FCFA');
                        $('#motif_intention').text(data.motif_intention ||
                            'Aucun motif spécifié');
                        $('#show_demandeur').text(data.demandeur || 'Anonyme');

                        // Gestion du badge statut
                        var statusText = data.status || 'En attente';
                        var badgeClass = 'bg-warning text-dark';

                        if (statusText.toLowerCase() === 'validée' || statusText ===
                            'ACCEPTED') {
                            badgeClass = 'bg-success';
                        } else if (statusText.toLowerCase() === 'refusée' || statusText ===
                            'REJECTED') {
                            badgeClass = 'bg-danger';
                        }

                        $('#show_status')
                            .text(statusText)
                            .removeClass()
                            .addClass('badge ' + badgeClass);

                        // 5. Afficher le contenu final
                        $('#modalLoader').hide();
                        $('#modalContent').fadeIn();
                    },
                    error: function() {
                        $('#modalLoader').hide();
                        myModal.hide();
                        Swal.fire('Erreur', 'Impossible de charger les détails.', 'error');
                    }
                });
            });

            // --- ACTIONS GROUPEES ---
            window.triggerBulkAction = function(type) {
                var selectedIds = [];
                $('.messe-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                if (selectedIds.length === 0) return;
                var url = (type === 'confirm') ? URL_BULK_CONFIRM : URL_BULK_CANCEL;
                var text = (type === 'confirm') ? "Ces demandes seront confirmées." :
                    "Ces demandes seront annulées.";
                executeAjax(url, selectedIds, type, text);
            };

            function executeAjax(url, ids, type, warningText) {
                var btnColor = (type === 'confirm') ? '#d4bd8a' : '#dc3545';
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: warningText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: btnColor,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, continuer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                selected_ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Succès!', 'Opération effectuée.', 'success');
                                table.ajax.reload();
                                $('#selectAll').prop('checked', false);
                                $('#bulkActions').hide();
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                                    .responseJSON.message : 'Erreur survenue.';
                                Swal.fire('Erreur', msg, 'error');
                            }
                        });
                    }
                });
            }
        });
    </script>
@endpush

<style>
    .table-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

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

    table.dataTable thead th {
        background-color: #5ea7b5;
        color: #ffffffff !important;
        vertical-align: middle;
    }

    .bulk-actions {
        background: #f1f3f5;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #dee2e6;
    }



    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #5ea7b5 !important;
        color: white !important;
        border: 1px solid #5ea7b5 !important;
    }
</style>
