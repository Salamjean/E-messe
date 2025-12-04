@extends('paroisse.layouts.template')

@section('content')
    <div class="container-fluid mt-4">

        {{-- Affichage des messages flash --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Zone de Filtres --}}
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-white ">
                <i class="fas fa-filter text-primary"></i> <strong>Filtres & Options</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="filter_sexe" class="form-label">Sexe</label>
                        <select id="filter_sexe" class="form-control form-select">
                            <option value="">Tous</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_situation" class="form-label">Situation Matrimoniale</label>
                        <select id="filter_situation" class="form-control form-select">
                            <option value="">Toutes</option>
                            <option value="Célibataire">Célibataire</option>
                            <option value="Marié(e)">Marié(e)</option>
                            <option value="Veuf(ve)">Veuf(ve)</option>
                            <option value="Divorcé(e)">Divorcé(e)</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                        <button id="btn_filter" class="btn btn-primary" style="background-color: #c49d54; border:none;">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <button id="btn_reset" class="btn btn-outline-secondary"
                            style="background-color: #d9d9d9; border:none;">
                            <i class="fas fa-undo"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- En-tête du tableau avec boutons d'export --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-primary fw-bold">Gestion des Paroissiens</h5>
                <div>
                    {{-- Les href sont mis à jour via JS en fonction des filtres --}}
                    <a href="{{ route('paroissien.export.pdf') }}" id="btn-export-pdf" style="background-color: #de353e;"
                        class="btn btn-danger me-1" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('paroissien.export.excel') }}" style="background-color: #339c5d;"
                        id="btn-export-excel" class="btn btn-success me-1">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('paroissien.create') }}" class="btn btn-primary "
                        style="background-color: #c49d54; border:none;">
                        <i class="fas fa-plus"></i> Nouveau
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="paroissienTable" class="table table-bordered table-striped table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nom & Prénoms</th>
                                <th>Téléphone</th>
                                <th>Sexe</th>
                                <th>Situation Matri.</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Assurez-vous d'avoir jQuery et DataTables inclus dans votre layout ou ici -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {

            // Initialisation de DataTables
            var table = $('#paroissienTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                language: {
                    // Vous pouvez télécharger ce fichier JSON ou utiliser l'URL CDN
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                },
                ajax: {
                    url: "{{ route('paroissien.data') }}",
                    data: function(d) {
                        // On envoie les valeurs des filtres au serveur
                        d.sexe = $('#filter_sexe').val();
                        d.situation_matrimoniale = $('#filter_situation').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nom_prenom',
                        name: 'nom_prenom'
                    },
                    {
                        data: 'telephone',
                        name: 'telephone'
                    },
                    {
                        data: 'sexe',
                        name: 'sexe'
                    },
                    {
                        data: 'situation_matrimoniale',
                        name: 'situation_matrimoniale'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ] // Trier par ID décroissant par défaut
            });

            // Gestionnaire d'événements pour le bouton Filtrer
            $('#btn_filter').click(function() {
                table.draw(); // Recharger le tableau avec les nouveaux filtres
                updateExportLinks(); // Mettre à jour les liens d'export
            });

            // Gestionnaire pour le bouton Reset
            $('#btn_reset').click(function() {
                $('#filter_sexe').val('');
                $('#filter_situation').val('');
                table.search(''); // Vider la barre de recherche globale
                table.draw();
                updateExportLinks();
            });

            // Fonction pour mettre à jour les liens d'export avec les filtres actuels
            function updateExportLinks() {
                var sexe = $('#filter_sexe').val();
                var sit = $('#filter_situation').val();
                var search = table.search(); // Récupère ce qui est tapé dans la recherche DataTables

                // Construction de la Query String
                var params = new URLSearchParams({
                    sexe: sexe,
                    situation_matrimoniale: sit,
                    search_term: search
                }).toString();

                var pdfBase = "{{ route('paroissien.export.pdf') }}";
                var excelBase = "{{ route('paroissien.export.excel') }}";

                $('#btn-export-pdf').attr('href', pdfBase + '?' + params);
                $('#btn-export-excel').attr('href', excelBase + '?' + params);
            }

            // Écouter l'événement de recherche DataTables pour mettre à jour les liens en temps réel
            table.on('search.dt', function() {
                updateExportLinks();
            });
        });
    </script>
@endpush


<style>
    .btn-action {
        width: 28px;
        /* largeur identique */
        height: 22px;
        /* hauteur identique */
        padding: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none !important;
    }

    .btn-view {
        background-color: #d9d9d9 !important;
    }

    .btn-edit {
        background-color: #c49d54 !important;
        color: white;
    }

    .btn-delete {
        background-color: #de353e !important;
        color: white;
    }

    .messe-header h1 {
        color: #d4bd8a;
        font-weight: 700;
    }

    table.dataTable thead th,
    table.dataTable thead td {
        background-color: #5ea7b5 !important;
        color: #ffffffff !important;
        vertical-align: middle;
    }
</style>
