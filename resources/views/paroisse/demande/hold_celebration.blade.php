@extends('paroisse.layouts.template')

@section('content')
    <!-- CSS DataTables & SweetAlert -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .messe-container {
            width: 95%;
            margin: 0 auto;
            padding: 20px 0;
        }

        .messe-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .messe-header h1 {
            color: #d4bd8a;
            font-weight: 700;
        }

        .page-item.active .page-link {
            background-color: #5ea7b5;
            border-color: #5ea7b5;
        }

        table.dataTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        .nom-badge {
            font-size: 0.85em;
            margin-right: 4px;
            margin-bottom: 4px;
            display: inline-block;
        }

        /* Style pour la modale */
        .modal-header {
            background-color: #5ea7b5;
            color: white;
        }

        .detail-group {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
            display: block;
        }

        .detail-value {
            color: #000;
        }

        table.dataTable thead th {
            background-color: #5ea7b5;
            color: #ffffffff;
            vertical-align: middle;
        }
    </style>

    <div class="messe-container">
        <div class="messe-header">
            <h1>Validation des intentions de messes</h1>
            <p>Retrouvez toutes vos demandes confirmées à venir sous forme de liste.</p>
        </div>

        <!-- Actions groupées (Commenté comme dans votre exemple, décommentez si besoin) -->
        {{-- <div id="bulkActions" class="bulk-actions" style="display: none; margin-bottom: 20px;">
            <span id="selectedCount" style="font-weight: 500; color: #333; margin-right: 15px;"></span>
            <button type="button" class="btn btn-success btn-sm" onclick="bulkAction('confirm')">
                ✅ Confirmer la sélection
            </button>
            <button type="button" class="btn btn-danger btn-sm" onclick="bulkAction('cancel')">
                ❌ Annuler la sélection
            </button>
        </div> --}}

        <!-- La Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
                <table id="messesTable" class="table table-hover dt-responsive nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center"><input type="checkbox" id="selectAll"
                                    class="form-check-input"></th>
                            <th>Date souhaitée</th>
                            <th>Heure souhaitée</th>
                            <th>Type celebration</th>
                            <th>Noms concernés</th>
                            <th>Montant de messes</th>
                            {{-- <th>Statut</th> --}}
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Les données seront chargées ici par Ajax -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Formulaire caché pour les actions groupées -->
    <form id="bulkActionForm" action="" method="POST" style="display: none;">
        @csrf
        @method('POST')
        <input type="hidden" name="selected_ids" id="bulkSelectedIds">
    </form>

    <!-- Modal de Détails -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails de la demande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 detail-group">
                            <span class="detail-label">📅 Date & Heure</span>
                            <span class="detail-value" id="modal-date-heure"></span>
                        </div>
                        <div class="col-md-6 detail-group">
                            <span class="detail-label">⛪ Célébration</span>
                            <span class="detail-value" id="modal-celebration"></span>
                        </div>
                        <div class="col-12 detail-group">
                            <span class="detail-label">🙏 Type d'intention</span>
                            <span class="detail-value" id="modal-type"></span>
                        </div>
                        {{-- <div class="col-12 detail-group">
                            <span class="detail-label">👥 Noms concernés</span>
                            <span class="detail-value" id="modal-noms"></span>
                        </div> --}}
                        <div class="col-12 detail-group">
                            <span class="detail-label">📝 Motif détaillé</span>
                            <p class="detail-value fst-italic mt-1" id="modal-motif"></p>
                        </div>
                        <div class="col-md-6 detail-group">
                            <span class="detail-label">👤 Demandeur</span>
                            <span class="detail-value" id="modal-demandeur"></span>
                        </div>
                        <div class="col-md-6 detail-group">
                            <span class="detail-label">📞 Contact</span>
                            <span class="detail-value" id="modal-contact"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#messesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ route('demandes.messes.celebrated') }}",
                    type: 'GET',
                    error: function(xhr, error, code) {
                        Swal.fire('Erreur', 'Impossible de charger les données: ' + code, 'error');
                    }
                },
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                },
                columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'date_souhaitee'
                    },
                    {
                        data: 'heure_souhaitee'
                    },
                    {
                        data: 'intention',
                    },
                    {
                        data: 'nom_concerne',
                    },
                    {
                        data: 'offrande'
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            // On ajoute un bouton qui déclenchera l'ouverture de la modal
                            return `<button class="btn btn-sm btn-info text-white btn-view-details">
                                        👁️ Voir
                                    </button>`;
                        }
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });

            // GESTION DU CLIC SUR LE BOUTON "VOIR"
            $('#messesTable tbody').on('click', '.btn-view-details', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var data = row.data();

                // Remplissage de la modale avec les données reçues du JSON
                $('#modal-date-heure').text(data.date_souhaitee + ' à ' + data.heure_souhaitee);
                $('#modal-celebration').text(data.full_details.celebration);
                $('#modal-type').text(data.intention);
                $('#modal-noms').text(data.full_details.noms_text);
                $('#modal-motif').text(data.full_details.motif);
                $('#modal-demandeur').text(data.full_details.demandeur);
                $('#modal-contact').text(data.full_details.telephone + ' / ' + data.full_details.email);

                // Ouverture de la modale
                var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                myModal.show();
            });

            // --- Gestion des Checkboxes (inchangé) ---
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
                var selected = $('.messe-checkbox:checked').length;
                if (selected > 0) {
                    $('#bulkActions').fadeIn();
                    $('#selectedCount').text(selected + ' sélectionné(s)');
                } else {
                    $('#bulkActions').fadeOut();
                }
            }

            window.bulkAction = function(type) {
                var selectedIds = [];
                $('.messe-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });
                if (selectedIds.length === 0) return;

                let route = type === 'confirm' ? "{{ route('paroisse.messe.bulk-confirm') }}" :
                    "{{ route('paroisse.messe.bulk-cancel') }}";
                let text = type === 'confirm' ? 'Confirmer' : 'Annuler';
                let color = type === 'confirm' ? '#28a745' : '#dc3545';

                Swal.fire({
                    title: text + ' la sélection',
                    text: `Action sur ${selectedIds.length} éléments.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: color,
                    confirmButtonText: 'Oui, ' + text.toLowerCase()
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#bulkSelectedIds').val(JSON.stringify(selectedIds));
                        $('#bulkActionForm').attr('action', route);
                        $('#bulkActionForm').submit();
                    }
                });
            };
        });
    </script>
@endpush
