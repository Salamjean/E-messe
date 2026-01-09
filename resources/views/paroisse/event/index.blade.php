@extends('paroisse.layouts.template')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('DataTables/dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    {{-- CORRECTION 3: CSS pour harmoniser les inputs du modal et espacer les boutons --}}
    <style>

    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        {{-- Messages flash --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="material-icons align-middle me-2">check_circle</i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="material-icons align-middle me-2">error</i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark mb-0" style="color: #d4bd8a !important;">
                <i class="material-icons align-middle me-2">event</i>
                Gestion des Événements
            </h3>

            <div>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger shadow-sm me-2" style="display: none;">
                    <i class="material-icons align-middle me-1">delete</i> Supprimer
                </button>
                <button type="button" id="addEventBtn" class="btn btn-add-event shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#eventModal">
                    <i class="material-icons align-middle me-1">add_circle</i> Ajouter un événement
                </button>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-filter active" data-filter="en_cours">En cours</button>
            <button class="btn btn-filter" data-filter="historique">Historique</button>
        </div>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="eventsTable" class="table table-hover table-striped align-middle"
                        style="width:100%; color: #d4bd8a !important;">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Date début</th>
                                {{-- CORRECTION 2: Ajout du header pour la date de fin --}}
                                <th>Date fin</th>
                                <th>Lieu</th>
                                <th>Célébrant</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Inclusion du modal séparé --}}
    @include('paroisse.event.modal.event')
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- <script src="{{ asset('DataTables/dataTables.min.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/event.js') }}"></script>

    <script>
        // --- Définition des routes pour JS (inchangé) ---
        window.eventRoutes = {
            data: "{{ route('event.data') }}",
            show: "{{ route('event.show', ':id') }}",
            store: "{{ route('event.store') }}",
            update: "{{ route('event.update', ':id') }}",
            destroy: "{{ route('event.destroy', ':id') }}",
            bulkDestroy: "{{ route('event.bulk-destroy') }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>
@endpush

<style>
    .messe-header h1 {
        color: #d4bd8a;
        font-weight: 700;
    }

    table.dataTable thead th {
        background-color: #5ea7b5 !important;
        color: #ffffffff !important;
        vertical-align: middle;
    }
</style>
