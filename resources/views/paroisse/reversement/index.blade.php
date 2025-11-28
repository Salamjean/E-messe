@extends('paroisse.layouts.template')

@section('title', 'Gestion des Reversements')

@section('styles')
    <!-- DataTables & Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        /* Vos styles existants conservés */
        .solde-card {
            background-color: #7ebac4;
            color: white;
            padding: 2rem 1rem;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: 0 4px 15px rgba(126, 186, 196, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .solde-icon {
            background: rgba(255, 255, 255, 0.25);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .solde-card h3 {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            opacity: 0.95;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .solde-card .montant {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            line-height: 1.2;
        }

        .solde-card .texte {
            font-size: 0.9rem;
            margin-top: 0.5rem;
            opacity: 0.85;
            font-style: italic;
        }

        .dataTables_processing {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 100;
        }

        .btn-reversement {
            background-color: #181824 !important;
            border-color: #181824 !important;
            color: #fff !important;
        }

        .btn-reversement:hover {
            background-color: #232334 !important;
            border-color: #232334 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 text-gray-800">Gestion des Reversements</h2>
            <button type="button" class="btn btn-reversement shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#modalReversement"
                style="width: 200px; color: #fff !important; background-color: #181824 !important; ">
                <i class="fas fa-plus me-2"></i>
                Nouveau Reversement
            </button>

        </div>

        <!-- Carte Solde Centrée -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-4 col-lg-4 col-xl-4">
                <div class="solde-card">
                    <div class="solde-icon">
                        <i class="fas fa-wallet"></i>
                    </div>

                    <h3
                        style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem; opacity: 0.95; text-transform: uppercase; letter-spacing: 1px;">
                        Solde disponible
                    </h3>

                    <span class="montant text-center"
                        style="font-size: 2.5rem; font-weight: 800; margin: 0; line-height: 1.2; color: #7ebac4;">
                        {{ number_format($soldeDisponible ?? 0, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>
        </div>

        <!-- Tableau des transactions -->
        <div class="card shadow border-0 rounded-3">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Historique des transactions</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-reversements" class="table table-striped table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Date</th>
                                <th>Destinataire</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajout Reversement -->
    <div class="modal fade" id="modalReversement" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <!-- Ajout de data-solde pour vérification JS -->
                <form id="form-reversement" data-solde="{{ $soldeDisponible ?? 0 }}">
                    @csrf
                    <div class="modal-header bg-light d-flex flex-column align-items-start">
                        <div class="w-100 d-flex justify-content-between align-items-center">
                            <h5 class="modal-title mb-0">
                                <i class="fas fa-mobile-alt me-2"></i> Transfert Mobile Money
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>

                        <div class="mt-2 w-100 text-center">
                            <span class="montant" style="font-size: 2rem; color: #7ebac4; font-weight: bold;">
                                Solde disponible : {{ number_format($soldeDisponible ?? 0, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Montant à transférer <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control col-md-6" name="montant" id="input-montant"
                                    min="100" placeholder="Ex: 5000" required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Opérateur</label>
                            <select class="form-select" name="payment_method">
                                <option value="">-- Laisser le destinataire choisir --</option>
                                <option value="MTN">MTN Mobile Money</option>
                                <option value="MOOV">Moov Money</option>
                                <option value="ORANGE">Orange Money</option>
                                <option value="WAVE">Wave</option>
                            </select>
                        </div>

                        <div class="row g-2">
                            <label class="form-label fw-bold">Numéro du destinataire <span
                                    class="text-danger">*</span></label>
                            <div class="col-4">
                                <select class="form-select" name="prefix">
                                    <option value="225">🇨🇮 +225</option>
                                    <option value="221">🇸🇳 +221</option>
                                    <option value="226">🇧🇫 +226</option>
                                </select>
                            </div>
                            <div class="col-8">
                                <input type="tel" class="form-control" name="telephone" placeholder="0707070707"
                                    pattern="[0-9]+" required>
                            </div>
                            <div class="col-12">
                                <small class="text-muted"><i class="fas fa-info-circle"></i> Saisissez le numéro sans
                                    l'indicatif pays.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary px-4" id="btn-submit"
                            style="width: 200px; color: #fff !important; background-color: #181824 !important; ">
                            <span class="spinner-border spinner-border-sm d-none me-1" role="status"
                                aria-hidden="true"></span>
                            Valider le transfert
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const table = $('#table-reversements').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('reversement.data') }}",
                    error: function(xhr, error, thrown) {
                        $('.dataTables_empty').text(
                            'Erreur de chargement des données. Veuillez rafraîchir.');
                    }
                },
                columns: [{
                        data: 'reference',
                        name: 'reference',
                        className: 'fw-bold'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'numero_destinataire',
                        name: 'numero_destinataire',
                        render: function(data, type, row) {
                            return `<span class="badge bg-light text-dark border">(+${row.prefix_pays}) ${data}</span>`;
                        }
                    },
                    {
                        data: 'montant',
                        name: 'montant',
                        className: 'text-end fw-bold',
                        render: $.fn.dataTable.render.number(' ', ',', 0, '', ' FCFA')
                    },
                    {
                        data: 'statut',
                        name: 'statut',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                }
            });

            $('#form-reversement').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const btn = $('#btn-submit');
                const spinner = btn.find('.spinner-border');

                // --- AJOUT : Vérification Frontend ---
                const montantDemande = parseFloat($('#input-montant').val());
                const soldeActuel = parseFloat(form.data('solde'));

                if (montantDemande > soldeActuel) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Solde Insuffisant',
                        text: 'Le montant demandé (' + montantDemande +
                            ' FCFA) est supérieur à votre solde disponible (' + soldeActuel +
                            ' FCFA).',
                        confirmButtonText: 'Corriger'
                    });
                    return; // On arrête l'exécution ici
                }
                // -------------------------------------

                btn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: "{{ route('reversement.store') }}",
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#modalReversement').modal('hide');
                        form[0].reset();
                        table.ajax.reload(null, false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Transfert Réussi',
                            text: response.message ||
                                'Le reversement a été initié avec succès.',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        // Note: Idéalement, recharger la page pour mettre à jour le solde affiché
                        // ou mettre à jour le DOM via JS si l'API renvoyait le nouveau solde
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    },
                    error: function(xhr) {
                        let errorMsg = "Une erreur inattendue s'est produite.";

                        // Gestion des messages d'erreur du backend
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Échec du transfert',
                            html: errorMsg, // Utilisation de html pour afficher les sauts de ligne si besoin
                            confirmButtonText: 'Compris'
                        });
                    },
                    complete: function() {
                        btn.prop('disabled', false);
                        spinner.addClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush
